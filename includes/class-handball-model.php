<?php

class Team
{

    private $teamId;

    private $teamName;

    private $leagueLong;

    private $leagueShort;

    private $saison;

    private $matches;

    public function __construct($teamId, $teamName, $saison)
    {
        $this->teamId = $teamId;
        $this->teamName = $teamName;
        $this->saison = $saison;
        $this->matches = [];
    }

    public function getTeamId()
    {
        return $this->teamId;
    }

    public function setTeamName($teamName) {
        $this->teamName = $teamName;
    }

    public function getLeagueLong()
    {
        return $this->leagueLong;
    }

    public function setLeagueLong($leagueLong)
    {
        $this->leagueLong = $leagueLong;
    }

    public function getLeagueShort()
    {
        return $this->leagueShort;
    }

    public function getPostTitle() {
        $post = $this->findPost();
        if ($post == null) {
            return '';
        }
        return $post->post_title;
    }

    public function getTeamUrl() {
        $post = $this->findPost();
        if ($post == null) {
            return '';
        }
        return get_permalink($post);
    }

    public function setLeagueShort($leagueShort)
    {
        $this->leagueShort = $leagueShort;
    }

    public function getTeamName()
    {
        return $this->teamName;
    }

    public function setSaison($saison)
    {
        $this->saison = $saison;
    }

    public function getSaison()
    {
        return new Saison($this->saison);
    }

    public function setMatches($matches)
    {
        $this->matches = $matches;
    }

    public function getMatches()
    {
        return $this->matches;
    }

    public function getSort()
    {
        $post = $this->findPost();
        if ($post == null) {
            return;
        }
        return get_post_meta($post->ID, 'handball_team_sort', true);
    }

    public function findPost() {
        $postQuery = new WP_Query([
            'post_type' => 'handball_team',
            'meta_query' => [
                [
                    'key' => 'handball_team_id',
                    'value' => $this->teamId
                ]
            ]
        ]);
        if ($postQuery->have_posts()) {
            $postQuery->the_post();
            return $postQuery->post;
        }
        return null;
    }

    public function getFirstImageUrlInPost()
    {
        $post = $this->findPost();
        if ($post == null) {
            return '';
        }
        ob_start();
        ob_end_clean();
        $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
        return $matches[1][0];
    }

    public function toString()
    {
        return 'Team [id=' . $this->teamId . ' name=' . $this->teamName . ' matches=' . count($this->matches) . ']';
    }
}

class Event
{
    private $post;

    public function __construct($post)
    {
        $this->post = $post;
    }

    public function isUpComing()
    {
        return time() < $this->getEndTimestamp();
    }

    private function getEndTimestamp()
    {
        return intval(get_post_meta($this->post->ID, 'handball_event_end_datetime', true));
    }

    public function getStartTimestamp()
    {
        return intval(get_post_meta($this->post->ID, 'handball_event_start_datetime', true));
    }

    public function formattedStartDateLong()
    {
        return date('d.m.Y', $this->getStartTimestamp());
    }

    public function formattedStartDateTimeLong()
    {
        return date('d.m.Y H:i', $this->getStartTimestamp());
    }

    public function getTitle()
    {
        return $this->post->post_title;
    }

    public function getUrl()
    {
        return get_permalink($this->post);
    }

}

class Saison
{

    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function formattedLong()
    {
        return substr_replace($this->value, '/', 4, 0);
    }

    public function formattedShort()
    {
        return substr($this->value, 2, 2) . '/' . substr($this->value, 6, 2);
    }

    public static function getCurrentSaison(): ?Saison
    {
        $currentSaison = get_option('HANDBALL_CURRENT_SAISON');
        if (empty($currentSaison)) {
            return null;
        }
        return new Saison($currentSaison);
    }

    public static function getCurrentSaisonBasedOnTime(): Saison
    {
        $saison = '';
        $currentMonth = intval(date('n'));
        $currentYear = date('Y');
        if ($currentMonth < 5) {
            $lastYear = date('Y', strtotime('-1 year'));
            $saison = $lastYear . $currentYear;
        } else {
            $nextYear = date('Y', strtotime('+1 year'));
            $saison = $currentYear . $nextYear;
        }
        return new Saison($saison);
    }
}

class Group
{

    private $groupId;
    private $groupText;
    private $leagueId;
    private $leagueLong;
    private $leagueShort;
    private $ranking;
    private $teamId;

    public function __construct($groupId, $teamId)
    {
        $this->groupId = $groupId;
        $this->teamId = $teamId;
    }

    public function getGroupId() {
        return $this->groupId;
    }

    public function getGroupText() {
        return $this->groupText;
    }

    public function setGroupText($groupText) {
        $this->groupText = $groupText;
    }

    public function getLeagueId() {
        return $this->leagueId;
    }

    public function setLeagueId($leagueId) {
        $this->leagueId = $leagueId;
    }

    public function getLeagueLong() {
        return $this->leagueLong;
    }

    public function setLeagueLong($leagueLong) {
        $this->leagueLong= $leagueLong;
    }

    public function getLeagueShort() {
        return $this->leagueShort;
    }

    public function setLeagueShort($leagueShort) {
        $this->leagueShort= $leagueShort;
    }

    public function getRankings(): array {
        if (empty($this->ranking)) {
            return [];
        }
        $rankingArray = json_decode($this->ranking);
        $rankings = [];
        foreach ($rankingArray as $rankingObject) {
            $rankings[] = new Ranking($rankingObject);
        }
        return $rankings;
    }

    public function setRanking($rankingJson)
    {
        $this->ranking = $rankingJson;
    }

    public function getTeamId()
    {
        return $this->teamId;
    }

}

class Ranking
{
    private $jsonObject;

    function __construct($jsonObject)
    {
        $this->jsonObject = $jsonObject;
    }

    public function getRank()
    {
        return $this->jsonObject->rank;
    }

    public function getTeamName()
    {
        return $this->jsonObject->teamName;
    }

    public function getTotalPoints()
    {
        return $this->jsonObject->totalPoints;
    }

    public function getTotalWins()
    {
        return $this->jsonObject->totalWins;
    }

    public function getTotalLoss()
    {
        return $this->jsonObject->totalLoss;
    }

    public function getTotalDraws()
    {
        return $this->jsonObject->totalDraws;
    }

    public function getTotalScoresPlus()
    {
        return $this->jsonObject->totalScoresPlus;
    }

    public function getTotalScoresMinus()
    {
        return $this->jsonObject->totalScoresMinus;
    }

    public function getTotalGames()
    {
        return $this->jsonObject->totalGames;
    }

    public function getTotalScoresDiff()
    {
        return $this->jsonObject->totalScoresDiff;
    }
}

class Match
{

    private $gameId;

    private $gameNr;

    // Reference Key to table hcg_match
    private $teamId;

    private $teamAName;

    private $teamBName;

    private $gameDateTime;

    private $gameTypeLong;

    private $gameTypeShort;

    private $leagueLong;

    private $leagueShort;

    private $round;

    private $gameStatus;

    private $teamAScoreHT;

    private $teamBScoreHT;

    private $teamAScoreFT;

    private $teamBScoreFT;

    private $venue;

    private $venueAddress;

    private $venueZip;

    private $venueCity;

    private $spectators;

    private $roundNr;

    public function __construct($gameId, $gameNr, $teamId)
    {
        $this->gameId = $gameId;
        $this->gameNr = $gameNr;
        $this->teamId = $teamId;
    }

    public function getGameId()
    {
        return $this->gameId;
    }

    public function setGameNr($gameNr)
    {
        $this->gameNr = $gameNr;
    }

    public function getGameNr()
    {
        return $this->gameNr;
    }

    public function getTeamId()
    {
        return $this->teamId;
    }

    public function setTeamAName($teamAName)
    {
        $this->teamAName = $teamAName;
    }

    public function getTeamAName()
    {
        return $this->teamAName;
    }

    public function setTeamBName($teamBName)
    {
        $this->teamBName = $teamBName;
    }

    public function getTeamBName()
    {
        return $this->teamBName;
    }

    public function setGameDateTime($gameDateTime)
    {
        $this->gameDateTime = $gameDateTime;
    }

    public function getGameDateTime()
    {
        return $this->gameDateTime;
    }

    public function getGameDateTimeFormattedLong() {
        return mysql2date('d.m.Y H:i', $this->getGameDateTime());
    }

    public function getGameDateTimeFormattedShort() {
        return mysql2date('d.m.y H:i', $this->getGameDateTime());
    }

    public function setGameTypeLong($gameTypeLong)
    {
        $this->gameTypeLong = $gameTypeLong;
    }

    public function getGameTypeLong()
    {
        return $this->gameTypeLong;
    }

    public function setGameTypeShort($gameTypeShort)
    {
        $this->gameTypeShort = $gameTypeShort;
    }

    public function getGameTypeShort()
    {
        return $this->gameTypeShort;
    }

    public function setLeagueLong($leagueLong)
    {
        $this->leagueLong = $leagueLong;
    }

    public function getLeagueLong()
    {
        return $this->leagueLong;
    }

    public function setLeagueShort($leagueShort)
    {
        $this->leagueShort = $leagueShort;
    }

    public function getLeagueShort()
    {
        return $this->leagueShort;
    }

    public function setRound($round)
    {
        $this->round = $round;
    }

    public function getRound()
    {
        return $this->round;
    }

    public function setGameStatus($gameStatus)
    {
        $this->gameStatus = $gameStatus;
    }

    public function getGameStatus()
    {
        return $this->gameStatus;
    }

    public function setTeamAScoreHT($teamAScoreHT)
    {
        $this->teamAScoreHT = $teamAScoreHT;
    }

    public function getTeamAScoreHT()
    {
        return $this->teamAScoreHT;
    }

    public function setTeamBScoreHT($teamBScoreHT)
    {
        $this->teamBScoreHT = $teamBScoreHT;
    }

    public function getTeamBScoreHT()
    {
        return $this->teamBScoreHT;
    }

    public function setTeamAScoreFT($teamAScoreFT)
    {
        $this->teamAScoreFT = $teamAScoreFT;
    }

    public function getTeamAScoreFT()
    {
        return $this->teamAScoreFT;
    }

    public function setTeamBScoreFT($teamBScoreFT)
    {
        $this->teamBScoreFT = $teamBScoreFT;
    }

    public function getTeamBScoreFT()
    {
        return $this->teamBScoreFT;
    }

    public function setVenue($venue)
    {
        $this->venue = $venue;
    }

    public function getVenue()
    {
        return $this->venue;
    }

    public function setVenueAddress($venueAddress)
    {
        $this->venueAddress = $venueAddress;
    }

    public function getVenueAddress()
    {
        return $this->venueAddress;
    }

    public function setVenueZip($venueZip)
    {
        $this->venueZip = $venueZip;
    }

    public function getVenueZip()
    {
        return $this->venueZip;
    }

    public function setVenueCity($venueCity)
    {
        $this->venueCity = $venueCity;
    }

    public function getVenueCity()
    {
        return $this->venueCity;
    }

    public function setSpectators($spectators)
    {
        $this->spectators = $spectators;
    }

    public function getSpectators()
    {
        return $this->spectators;
    }

    public function setRoundNr($roundNr)
    {
        $this->roundNr = $roundNr;
    }

    public function getRoundNr()
    {
        return $this->roundNr;
    }

    public function getEncounter() {
        return $this->getTeamAName() . ' - ' . $this->getTeamBName();
    }

    public function getScore() {
        return $this->getTeamAScoreFT() . ':' . $this->getTeamBScoreFT()
        . ' (' . $this->getTeamAScoreHT() . ':' . $this->getTeamBScoreHT() . ')'
        ;
    }

    public function getPostPreview() {
        return $this->findPost('preview');
    }

    public function getPostPreviewUrl() {
        $post = $this->getPostPreview();
        return self::getPermalinkForPost($post);
    }

    public function getPostReport() {
        return $this->findPost('report');
    }

    public function getPostReportUrl() {
        $post = $this->getPostReport();
        return self::getPermalinkForPost($post);
    }

    private static function getPermalinkForPost($post) {
        if ($post == null) {
            return null;
        }
        return get_permalink($post);
    }

    private function findPost($gameReportType)
    {
        $postQuery = new WP_Query([
            'post_type' => 'handball_match',
            'meta_query' => [
                [
                    'key' => 'handball_game_id',
                    'value' => $this->gameId
                ], [
                    'key' => 'handball_game_report_type',
                    'value' => $gameReportType
                ]
            ]
        ]);
        if ($postQuery->have_posts()) {
            $postQuery->the_post();
            return $postQuery->post;
        }
        return null;
    }

    public function toString()
    {
        return 'Match [id=' . $this->gameId . ' teamAName=' . $this->teamAName . ' teamBName=' . $this->teamBName . ']';
    }
}