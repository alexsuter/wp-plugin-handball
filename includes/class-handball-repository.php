<?php
require_once ('class-handball-model.php');

abstract class Repository
{
    protected $wpdb;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
    }
}

class HandballTeamRepository extends Repository
{
    public function saveTeam(Team $team)
    {
        // Apply saison
        $existingTeam = $this->findById($team->getTeamId());
        if ($existingTeam == null) {
            $team->setSaison(Saison::getCurrentSaison()->getValue());
        } else {
            $team->setSaison($existingTeam->getSaison()->getValue());
        }

        // Save Team
        $data = [
            'team_id' => $team->getTeamId(),
            'team_name' => $team->getTeamName(),
            'saison' => $team->getSaison()->getValue(),
            'leagues_json' => json_encode($team->getLeagues())
        ];
        $format = [
            '%d',
            '%s',
            '%s',
            '%s'
        ];
        if ($existingTeam == null) {
            $this->wpdb->insert('handball_team', $data, $format);
        } else {
            $this->wpdb->update('handball_team', $data, [
                'team_id' => $team->getTeamId()
            ], $format, [
                '%d'
            ]);
        }
    }

    public function findAll($saison)
    {
        $query = $this->wpdb->prepare('SELECT * FROM handball_team WHERE saison = %s', $saison);
        $results = $this->wpdb->get_results($query);
        return $this->mapTeams($results);
    }

    private function findById($id)
    {
        $query = $this->wpdb->prepare('SELECT * FROM handball_team WHERE team_id = %d', $id);
        $row = $this->wpdb->get_row($query);
        return $row ? $this->mapTeam($row) : null;
    }

    private function mapTeam($dbTeam)
    {
        $team = new Team($dbTeam->team_id, $dbTeam->team_name, $dbTeam->saison);
        if (!empty($dbTeam->leagues_json)) {
            $leagues = json_decode($dbTeam->leagues_json);
            foreach ($leagues as $league) {
                $team->addLeague($league->leagueId, $league->groupText);
            }
        }
        return $team;
    }

    private function mapTeams($dbTeams)
    {
        $teams = [];
        foreach ($dbTeams as $dbTeam) {
            $teams[] = $this->mapTeam($dbTeam);
        }
        return $teams;
    }

}

class HandballSaisonRepository extends Repository
{
    public function findAll()
    {
        $saisons = $this->wpdb->get_results('SELECT DISTINCT saison FROM handball_team ORDER BY saison ASC');
        $map = array_map(function ($saison) { return new Saison($saison->saison); }, $saisons);
        return $map;
    }
}

class HandballMatchRepository extends Repository
{
    public function saveMatch(Match $match)
    {
        $data = [
            'game_id' => $match->getGameId(),
            'game_nr' => $match->getGameNr(),
            'fk_team_id' => $match->getTeamId(),
            'game_datetime' => $match->getGameDateTime(),
            'team_a_name' => $match->getTeamAName(),
            'team_b_name' => $match->getTeamBName(),
            'game_type_long' => $match->getGameTypeLong(),
            'game_type_short' => $match->getGameTypeShort(),
            'venue' => $match->getVenue(),
            'venue_address' => $match->getVenueAddress(),
            'venue_city' => $match->getVenueCity(),
            'venue_zip' => $match->getVenueZip(),
            'league_long' => $match->getLeagueLong(),
            'league_short' => $match->getLeagueShort(),
            'round' => $match->getRound(),
            'game_status' => $match->getGameStatus(),
            'team_a_score_ht' => $match->getTeamAScoreHT(),
            'team_a_score_ft' => $match->getTeamAScoreFT(),
            'team_b_score_ht' => $match->getTeamBScoreHT(),
            'team_b_score_ft' => $match->getTeamBScoreFT(),
            'spectators' => $match->getSpectators(),
            'round_nr' => $match->getRoundNr(),
        ];
        $format = [
            '%d',
            '%d',
            '%d',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%d',
            '%d',
            '%d',
            '%d',
            '%d',
            '%d',
        ];

        $existingMatch = $this->findById($match->getGameId());
        if ($existingMatch == null) {
            $this->wpdb->insert('handball_match', $data, $format);
        } else {
            $this->wpdb->update('handball_match', $data, [
                'game_id' => $match->getGameId()
            ], $format, [
                '%d'
            ]);
        }
    }

    public function findAll()
    {
        $dbMatches = $this->wpdb->get_results('SELECT * FROM handball_match ORDER BY game_datetime');
        return $this->mapMatches($dbMatches);
    }

    public function findMatchesNextWeek() {
        $dbMatches = $this->wpdb->get_results('SELECT * FROM handball_match
            WHERE game_datetime < (DATE_ADD(CURDATE(), INTERVAL 1 WEEK)) AND game_datetime > (CURDATE())
            ORDER BY game_datetime ASC');
        return $this->mapMatches($dbMatches);
    }

    public function findMatchesLastWeek() {
        $dbMatches = $this->wpdb->get_results('SELECT * FROM handball_match
            WHERE game_datetime > (DATE_SUB(CURDATE(), INTERVAL 1 WEEK)) AND game_datetime < (CURDATE())
            ORDER BY game_datetime ASC');
        return $this->mapMatches($dbMatches);
    }

    private function findById($id)
    {
        $query = $this->wpdb->prepare('SELECT * FROM handball_match WHERE game_id = %d', $id);
        $row = $this->wpdb->get_row($query);
        return $row ? $this->mapMatch($row) : null;
    }

    private function mapMatch($dbMatch)
    {
        $match= new Match($dbMatch->game_id, $dbMatch->game_nr, $dbMatch->fk_team_id);
        $match->setTeamAName($dbMatch->team_a_name);
        $match->setTeamBName($dbMatch->team_b_name);
        $match->setGameDateTime($dbMatch->game_datetime);
        $match->setLeagueShort($dbMatch->league_short);
        $match->setLeagueLong($dbMatch->league_long);
        $match->setTeamAScoreFT($dbMatch->team_a_score_ft);
        $match->setTeamBScoreFT($dbMatch->team_b_score_ft);
        $match->setTeamAScoreHT($dbMatch->team_a_score_ht);
        $match->setTeamBScoreHT($dbMatch->team_b_score_ht);
        $match->setSpectators($dbMatch->spectators);
        $match->setRoundNr($dbMatch->round_nr);
        $match->setRound($dbMatch->round);
        $match->setGameTypeShort($dbMatch->game_type_short);
        $match->setGameTypeLong($dbMatch->game_type_short);
        $match->setGameStatus($dbMatch->game_status);
        $match->setVenue($dbMatch->venue);
        $match->setVenueCity($dbMatch->venue_city);
        $match->setVenueZip($dbMatch->venue_zip);
        $match->setVenueAddress($dbMatch->venue_address);
        return $match;
    }

    private function mapMatches($dbMatches)
    {
        $matches = [];
        foreach ($dbMatches as $dbMatch) {
            $matches[] = $this->mapMatch($dbMatch);
        }
        return $matches;
    }
}