<?php
if (! class_exists('WP_List_Table')) {
    require_once (ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

require_once ('class-handball-model.php');
require_once ('class-handball-repository.php');

class HandballMatchList extends WP_List_Table
{

    private $matchRepo;

    public function __construct($args = [])
    {
        parent::__construct($args);
        $this->matchRepo = new HandballMatchRepository();
    }

    function get_columns()
    {
        return [
            'game_id' => 'SHV Game ID',
            'game_nr' => 'SHV Game Nr',
            'game_datetime' => 'Datum',
            'league_short' => 'Liga',
            'team_a_name' => 'Team A',
            'team_b_name' => 'Team B',
            'venue_city' => 'Ort'
        ];
    }

    function prepare_items()
    {
        $columns = $this->get_columns();
        $hidden = [];
        $sortable = [];
        $this->_column_headers = [
            $columns,
            $hidden,
            $sortable
        ];

        $this->items = $this->matchRepo->findAll();
    }

    function column_default($item, $column_name)
    {
        switch ($column_name) {
            case 'game_id':
                return $item->getGameId();
            case 'game_nr':
                return $item->getGameNr();
            case 'game_datetime':
                return mysql2date(get_option('date_format') . ' ' . get_option('time_format'), $item->getGameDateTime());
            case 'league_short':
                return $item->getLeagueShort();
            case 'team_a_name':
                return $item->getTeamAName();
            case 'team_b_name':
                return $item->getTeamBName();
            case 'venue_city':
                return $item->getVenueCity();
        }
    }

}