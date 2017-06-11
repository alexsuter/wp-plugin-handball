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

    protected function mapObjects($rows)
    {
        return array_map([$this, 'mapObject'], $rows);
    }

    protected function findOne($query)
    {
        $row = $this->wpdb->get_row($query);
        return $row ? $this->mapObject($row) : null;
    }

    protected function findMultiple($query)
    {
        $results = $this->wpdb->get_results($query);
        return $this->mapObjects($results);
    }
}

class HandballTeamRepository extends Repository
{
    public function saveTeam(Team $team)
    {
        $data = [
            'team_id' => $team->getTeamId(),
            'team_name' => $team->getTeamName(),
            'saison' => $team->getSaison()->getValue(),
            'league_short' => $team->getLeagueShort(),
            'league_long' => $team->getLeagueLong()
        ];
        $format = [
            '%d',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%d',
            '%d'
        ];
        if ($this->existsTeam($team->getTeamId())) {
            $this->wpdb->update('handball_team', $data, ['team_id' => $team->getTeamId()], $format, ['%d']);
        } else {
            $this->wpdb->insert('handball_team', $data, $format);
        }
    }

    private function existsTeam($teamId)
    {
        return $this->findById($teamId) != null;
    }

    public function findAll(): array
    {
        $query = 'SELECT * FROM handball_team ORDER BY saison DESC';
        return $this->findMultiple($query);
    }

    public function findAllBySaisonWithPost(?Saison $saison): array {
        $teams = $this->findAllBySaison($saison);
        $t = [];
        foreach ($teams as $team) {
            if ($team->findPost() != null) {
                $t[] = $team;
            }
        }
        return $t;
    }

    public function findAllBySaison(?Saison $saison): array
    {
        if ($saison == null) {
            return [];
        }
        $query = $this->wpdb->prepare('SELECT * FROM handball_team WHERE saison = %s ORDER BY saison DESC', $saison->getValue());
        $teams = $this->findMultiple($query);
        usort($teams, function (Team $teamA, Team $teamB) {
            $aSort = empty($teamA->getSort()) ? 100000 : $teamA->getSort();
            $bSort = empty($teamB->getSort()) ? 100000 : $teamB->getSort();
            return $aSort > $bSort;
        });
        return $teams;
    }

    public function findById($id)
    {
        $query = $this->wpdb->prepare('SELECT * FROM handball_team WHERE team_id = %d', $id);
        return $this->findOne($query);
    }

    protected function mapObject($row): Team
    {
        $team = new Team($row->team_id, $row->team_name, $row->saison);
        $team->setLeagueLong($row->league_long);
        $team->setLeagueShort($row->league_short);
        return $team;
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

class HandballEventRepository
{

    public function findUpComingEvents()
    {
        $events = $this->loadPostsOfTypeEvent(function ($event) {
            return $event->isUpComing();
        });
        usort($events, function (Event $a, Event $b) {
            return $a->getStartTimestamp() > $b->getStartTimestamp();
        });
        return $events;
    }

    public function findPastEvents()
    {
        $events = $this->loadPostsOfTypeEvent(function ($event) {
            return ! $event->isUpComing();
        });
        usort($events, function (Event $a, Event $b) {
            return $a->getStartTimestamp() < $b->getStartTimestamp();
        });
        return $events;
    }

    private function loadPostsOfTypeEvent($filterCallable) {
        $postQuery = new WP_Query([
            'post_type' => 'handball_event'
        ]);
        $events = [];
        while ($postQuery->have_posts()) {
            $postQuery->the_post();
            $event = new Event($postQuery->post);
            if ($filterCallable($event)) {
                $events[] = $event;
            }
        }
        return $events;
    }
}

class HandballGroupRepository extends Repository
{
    public function saveGroup(Group $group) {
        $data = [
            'group_id' => $group->getGroupId(),
            'group_text' => $group->getGroupText(),
            'league_id' => $group->getLeagueId(),
            'league_short' => $group->getLeagueShort(),
            'league_long' => $group->getLeagueLong(),
            'ranking' => $group->getRanking(),
            'fk_team_id' => $group->getTeamId()
        ];
        $format = [
            '%d',
            '%s',
            '%d',
            '%s',
            '%s',
            '%s',
            '%d'
        ];
        if ($this->existsGroup($group->getGroupId())) {
            $this->wpdb->update('handball_group', $data, ['group_id' => $group->getGroupId()], $format, ['%d']);
        } else {
            $this->wpdb->insert('handball_group', $data, $format);
        }
    }

    public function findGroupsByTeamId($teamId): array
    {
        $query = $this->wpdb->prepare('SELECT * FROM handball_group WHERE fk_team_id = %d', $teamId);
        return $this->findMultiple($query);
    }

    public function findById($groupId): ?Group
    {
        $query = $this->wpdb->prepare('SELECT * FROM handball_group WHERE group_id = %d', $groupId);
        return $this->findOne($query);
    }

    protected function mapObject($row): Group
    {
        $group = new Group($row->group_id, $row->fk_team_id);
        $group->setGroupText($row->group_text);
        $group->setLeagueId($row->league_id);
        $group->setLeagueLong($row->league_long);
        $group->setLeagueShort($row->league_short);
        $group->setRanking($row->ranking);
        return $group;
    }

    private function existsGroup($groupId)
    {
        return $this->findById($groupId) != null;
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
            $this->wpdb->update('handball_match', $data, ['game_id' => $match->getGameId()], $format, ['%d']);
        }
    }

    public function findAll(): array
    {
        return $this->findMultiple('SELECT * FROM handball_match ORDER BY game_datetime');
    }

    public function findMatchesForTeam($teamId): array
    {
        $query = $this->wpdb->prepare('SELECT * FROM handball_match WHERE fk_team_id = %d ORDER BY game_datetime', $teamId);
        return $this->findMultiple($query);
    }

    public function findMatchesNextWeek(): array {
        $query = 'SELECT * FROM handball_match
            WHERE game_datetime < (DATE_ADD(CURDATE(), INTERVAL 1 WEEK)) AND game_datetime > (CURDATE())
            ORDER BY game_datetime ASC';
        return $this->findMultiple($query);
    }

    public function findMatchesLastWeek(): array {
        $query = 'SELECT * FROM handball_match
            WHERE game_datetime > (DATE_SUB(CURDATE(), INTERVAL 1 WEEK)) AND game_datetime < (CURDATE())
            ORDER BY game_datetime ASC';
        return $this->findMultiple($query);
    }

    public function findById($id): ?Match
    {
        $query = $this->wpdb->prepare('SELECT * FROM handball_match WHERE game_id = %d', $id);
        return $this->findOne($query);
    }

    protected function mapObject($row)
    {
        $match= new Match($row->game_id, $row->game_nr, $row->fk_team_id);
        $match->setTeamAName($row->team_a_name);
        $match->setTeamBName($row->team_b_name);
        $match->setGameDateTime($row->game_datetime);
        $match->setLeagueShort($row->league_short);
        $match->setLeagueLong($row->league_long);
        $match->setTeamAScoreFT($row->team_a_score_ft);
        $match->setTeamBScoreFT($row->team_b_score_ft);
        $match->setTeamAScoreHT($row->team_a_score_ht);
        $match->setTeamBScoreHT($row->team_b_score_ht);
        $match->setSpectators($row->spectators);
        $match->setRoundNr($row->round_nr);
        $match->setRound($row->round);
        $match->setGameTypeShort($row->game_type_short);
        $match->setGameTypeLong($row->game_type_short);
        $match->setGameStatus($row->game_status);
        $match->setVenue($row->venue);
        $match->setVenueCity($row->venue_city);
        $match->setVenueZip($row->venue_zip);
        $match->setVenueAddress($row->venue_address);
        return $match;
    }
}