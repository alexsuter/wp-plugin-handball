<?php

class Team
{

    private $teamId;

    private $teamName;

    private $leagueLong;

    private $leagueShort;

    private $saison;

    private $leagues;

    private $matches;

    private $sort;

    private $imageId;

    public function __construct($teamId, $teamName, $saison)
    {
        $this->teamId = $teamId;
        $this->teamName = $teamName;
        $this->saison = $saison;
        $this->leagues = [];
        $this->matches = [];
    }

    public function getTeamId()
    {
        return $this->teamId;
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

    public function setImageId($imageId)
    {
        $this->imageId = $imageId;
    }

    public function getTeamUrl() {
        return '/teams/' . $this->getTeamId();
    }

    public function getImageId()
    {
        return $this->imageId;
    }

    public function hasImage()
    {
        return wp_attachment_is_image($this->imageId);
    }

    public function getImageUrl()
    {
        return wp_get_attachment_url($this->getImageId());
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

    public function addLeague($id, $groupText)
    {
        $this->leagues[] = new League($id, $groupText);
    }

    public function getLeagues()
    {
        return $this->leagues;
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
        return $this->sort;
    }

    public function setSort($sort)
    {
        $this->sort = $sort;
    }

    public function toString()
    {
        return 'Team [id=' . $this->teamId . ' name=' . $this->teamName . ' leagues=' . count($this->leagues) . ' matches=' . count($this->matches) . ']';
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

    public static function getCurrentSaison(): Saison
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

class League implements JsonSerializable
{

    private $leagueId;

    private $groupText;

    public function __construct($leagueId, $groupText)
    {
        $this->leagueId = $leagueId;
        $this->groupText = $groupText;
    }

    public function getLeagueId()
    {
        return $this->leagueId;
    }

    public function getGroupText()
    {
        return $this->groupText;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
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