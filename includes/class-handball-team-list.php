<?php
if (! class_exists('WP_List_Table')) {
    require_once (ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

require_once ('class-handball-model.php');
require_once ('class-handball-repository.php');

class HandballTeamList extends WP_List_Table
{

    private $teamRepo;

    public function __construct($args = [])
    {
        parent::__construct($args);
        $this->teamRepo = new HandballTeamRepository();
    }

    function get_columns()
    {
        return [
            'team_id' => 'SHV Team ID',
            'saison' => 'Saison',
            'team_name' => 'SHV Team Name'
        ];
    }

    function prepare_items()
    {
        $columns = $this->get_columns();
        $hidden = [];
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = [
            $columns,
            $hidden,
            $sortable
        ];

        // TOOD use php 7 ??
        $orderBy = (! empty($_GET['orderby'])) ? $_GET['orderby'] : 'team_name';
        $order = (! empty($_GET['order'])) ? $_GET['order'] : 'asc';

        $this->items = $this->teamRepo->findAll($orderBy, $order);
    }

    function column_default($item, $column_name)
    {
        switch ($column_name) {
            case 'team_id':
                return $item->getTeamId();
            case 'saison':
                return $item->getSaison();
            case 'team_name':
                return $item->getTeamName();
        }
    }

    function get_sortable_columns()
    {
        return [
            'team_name' => [
                'team_name',
                false
            ]
        ];
    }
}