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
            'datetime' => 'Datum',
            'league' => 'Liga',
            'encounter' => 'Begegnung',
            'venue' => 'Ort',
            'actions'  => 'Aktionen',
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
            case 'datetime':
                return $item->getGameDateTimeFormattedShort();
            case 'league':
                return $item->getLeagueShort();
            case 'encounter':
                return $item->getEncounter() . '<br />' . $item->getScore();
            case 'venue':
                return $item->getVenue();
            case 'actions':
                return $this->createActionLink($item->getGameId(), 'preview')
                . '<br />' . $this->createActionLink($item->getGameId(), 'report');
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

        $type = 'Bericht';
        if ($gameReportType == 'preview') {
            $type = 'Vorschau';
        }

        $icon = 'plus';
        $url  = '/wp-admin/post-new.php?post_type=handball_match&handball_game_report_type='.$gameReportType.'&handball_game_id='.$gameId;
        if ($loop->have_posts()) {
            $loop->the_post();
            $icon = 'edit';
            $url = '/wp-admin/post.php?post='.$loop->post->ID.'&action=edit';
        }

        return '</div><a class="wp-menu-image dashicons-before dashicons-'.$icon.'" href="'.$url.'">'.$type .'</a>';
    }
}