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
            'venue_city' => 'Ort',
            'preview_link'  => 'Vorschau',
            'report_link'  => 'Bericht',
        ];
    }

    function prepare_items($nextWeek = false, $lastWeek = false)
    {
        $columns = $this->get_columns();
        $hidden = [];
        $sortable = [];
        $this->_column_headers = [
            $columns,
            $hidden,
            $sortable
        ];

        if ($nextWeek) {
            $this->items = $this->matchRepo->findMatchesNextWeek();
        } else if ($lastWeek) {
            $this->items = $this->matchRepo->findMatchesLastWeek();
        } else {
            $this->items = $this->matchRepo->findAll();
        }
    }

    function column_default($item, $column_name)
    {
        switch ($column_name) {
            case 'game_id':
                return $item->getGameId();
            case 'game_nr':
                return $item->getGameNr();
            case 'game_datetime':
                return $item->getGameDateTimeFormattedLong();
            case 'league_short':
                return $item->getLeagueShort();
            case 'team_a_name':
                return $item->getTeamAName();
            case 'team_b_name':
                return $item->getTeamBName();
            case 'venue_city':
                return $item->getVenueCity();
            case 'preview_link':
                return $this->createActionLink($item->getGameId(), 'preview');
            case 'report_link':
                return $this->createActionLink($item->getGameId(), 'report');
        }
    }

    private function createActionLink($gameId, $gameReportType)
    {
        $loop = new WP_Query([
            'post_type' => 'handball_match',
            'meta_query' => [
                [
                    'key' => 'handball_game_id',
                    'value' => $gameId
                ], [
                    'key' => 'handball_game_report_type',
                    'value' => $gameReportType
                ]
            ]
        ]);

        $text = 'Erstellen';
        $url  = '/wp-admin/post-new.php?post_type=handball_match&handball_game_report_type='.$gameReportType.'&handball_game_id='.$gameId;
        if ($loop->have_posts()) {
            $loop->the_post();
            $text = 'Bearbeiten';
            $url = '/wp-admin/post.php?post='.$loop->post->ID.'&action=edit';
        }

        return '<a href="'.$url.'">'.$text.'</a>';
    }
}