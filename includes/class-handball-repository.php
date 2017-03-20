<?php
require_once ('class-handball-model.php');

class HandballTeamRepository
{

    private $wpdb;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
    }

    public function saveTeam(Team $team)
    {
        // apply saion
        $row = $this->wpdb->get_row('SELECT * FROM hcg_team WHERE id = ' . $team->getTeamId());
        if ($row) {
            $team->setSaison($row->saison);
        }

        $data = [
            'team_id' => $team->getTeamId(),
            'team_name' => $team->getTeamName(),
            'saison' => $team->getSaison()
        ];
        $format = [
            '%d',
            '%s',
            '%s'
        ];

        $row = $this->wpdb->get_row('SELECT * FROM hcg_team WHERE id = ' . $team->getTeamId());
        if ($row) {
            $this->wpdb->update('hcg_team', $data, [
                'team_id' => $team->getTeamId()
            ], $format, [
                '%d'
            ]);
        } else {
            $this->wpdb->insert('hcg_team', $data, $format);
        }
    }

    public function findAll($orderBy = 'id', $order = 'ASC')
    {
        $orderByClause = 'ORDER BY ' . $orderBy . ' ' . $order; // TODO CHECK OF INJECTION

        $dbTeams = $this->wpdb->get_results('SELECT * FROM hcg_team ' . $orderByClause);

        $teams = [];
        foreach ($dbTeams as $dbTeam) {
            $teams[] = new Team($dbTeam->team_id, $dbTeam->team_name, $dbTeam->saison);
        }

        return $teams;
    }
}

class HandballMatchRepository
{

    private $wpdb;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
    }

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

        $row = $this->wpdb->get_row('SELECT * FROM hcg_match WHERE game_id = ' . $match->getGameId());
        if ($row) {
            $this->wpdb->update('hcg_match', $data, [
                'game_id' => $match->getGameId()
            ], $format, [
                '%d'
            ]);
        } else {
            $this->wpdb->insert('hcg_match', $data, $format);
        }
    }

    public function findAll()
    {
        $dbMatches = $this->wpdb->get_results('SELECT * FROM hcg_match');

        $matches = [];
        foreach ($dbMatches as $dbMatch) {
            $match= new Match($dbMatch->game_id, $dbMatch->game_nr, $dbMatch->fk_team_id);
            $match->setTeamAName($dbMatch->team_a_name);
            $match->setTeamBName($dbMatch->team_b_name);
            $match->setGameDateTime($dbMatch->game_datetime);
            $match->setLeagueShort($dbMatch->league_short);
            $match->setLeagueLong($dbMatch->league_long);
            $match->setVenue($dbMatch->venue);
            $match->setVenueCity($dbMatch->venue_city);
            $match->setVenueZip($dbMatch->venue_zip);
            $match->setVenueAddress($dbMatch->venue_address);
            $matches[] = $match;
        }

        return $matches;
    }
}