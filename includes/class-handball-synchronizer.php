<?php
require_once ('class-handball-model.php');
require_once ('class-handball-repository.php');

class HandballSynchronizer
{

    private $apiUrl;

    private $apiUsername;

    private $apiPassword;

    private $clubId;

    private $teamRepo;

    private $matchRepo;

    public function __construct($apiUrl, $apiUsername, $apiPassword, $clubId)
    {
        $this->apiUrl = $apiUrl;
        $this->apiUsername = $apiUsername;
        $this->apiPassword = $apiPassword;
        $this->clubId = $clubId;
        $this->teamRepo = new HandballTeamRepository();
        $this->matchRepo = new HandballMatchRepository();
    }

    public function start()
    {
        if (! $this->validConfig()) {
            return;
        }

        $teams = $this->fetchTeams($this->clubId);

        foreach ($teams as $team) {
            $this->teamRepo->saveTeam($team);
        }

        foreach ($teams as $team) {
            $matches = self::fetchMatches($team->getTeamId());
            foreach ($matches as $match) {
                $this->matchRepo->saveMatch($match);
            }
        }
    }

    private function validConfig()
    {
        if (empty($this->apiUrl)) {
            return false;
        }
        if (empty($this->apiUsername)) {
            return false;
        }
        if (empty($this->apiPassword)) {
            return false;
        }
        if (empty($this->clubId)) {
            return false;
        }
        return true;
    }

    private function fetchTeams($clubId)
    {
        $responseTeams = $this->fetchBody($this->apiUrl . '/clubs/' . $clubId . '/teams');

        $teams = [];
        foreach ($responseTeams as $responseTeam) {
            $id = $responseTeam->teamId;
            if (! isset($teams[$id])) {
                $teams[$id] = new Team($id, $responseTeam->teamName, null);
            }
            $team = $teams[$id];
            $team->addLeague($responseTeam->leagueId, $responseTeam->groupText);
        }

        return $teams;
    }

    private function fetchMatches($teamId)
    {
        $responseMatches = $this->fetchBody($this->apiUrl . '/teams/' . $teamId . '/games');

        $matches = [];
        foreach ($responseMatches as $responseMatch) {
            $match = new Match($responseMatch->gameId, $responseMatch->gameNr, $teamId);
            $match->setTeamAName($responseMatch->teamAName);
            $match->setTeamBName($responseMatch->teamBName);
            $match->setGameDateTime($responseMatch->gameDateTime);
            $match->setGameTypeLong($responseMatch->gameTypeLong);
            $match->setGameTypeShort($responseMatch->gameTypeShort);
            $match->setLeagueLong($responseMatch->leagueLong);
            $match->setLeagueShort($responseMatch->leagueShort);
            $match->setGameStatus($responseMatch->gameStatus);
            $match->setRound($responseMatch->round);
            $match->setRoundNr($responseMatch->roundNr);
            $match->setSpectators($responseMatch->spectators);
            $match->setTeamAScoreFT($responseMatch->teamAScoreFT);
            $match->setTeamAScoreHT($responseMatch->teamAScoreHT);
            $match->setTeamBScoreFT($responseMatch->teamBScoreFT);
            $match->setTeamBScoreHT($responseMatch->teamBScoreHT);
            $match->setVenue($responseMatch->venue);
            $match->setVenueAddress($responseMatch->venueAddress);
            $match->setVenueCity($responseMatch->venueCity);
            $match->setVenueZip($responseMatch->venueZip);
            $matches[] = $match;
        }
        return $matches;
    }

    private function fetchBody($url)
    {
        $response = wp_remote_get($url, $this->createRequestArguments());
        $body = wp_remote_retrieve_body($response);
        return json_decode($body);
    }

    private function createRequestArguments()
    {
        return [
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode($this->apiUsername . ':' . $this->apiPassword)
            ]
        ];
    }

    private static function log($content)
    {
        $myfile = fopen(dirname(__FILE__) . '/log.txt', 'a');
        fwrite($myfile, $content . "\n");
        fclose($myfile);
    }
}
