<?php

class Team
{

    private $teamId;

    private $teamName;

    private $saison;

    private $leagues;

    private $matches;

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
    public function getValue() {
        return $this->value;
    }
    public function formattedLong() {
        return substr_replace($this->value, '/', 4, 0);
    }

    public function formattedShort() {
        return substr($this->value, 2, 2) . '/' . substr($this->value, 6, 2);
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
        return mysql2date('d.m.Y h:i', $this->getGameDateTime());
    }

    public function getGameDateTimeFormattedShort() {
        return mysql2date('d.m.y h:i', $this->getGameDateTime());
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

    public function toString()
    {
        return 'Match [id=' . $this->gameId . ' teamAName=' . $this->teamAName . ' teamBName=' . $this->teamBName . ']';
    }
}