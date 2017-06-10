<?php

class HandballPublicPlugin
{

    private $pluginName;

    private $version;

    public function __construct($pluginName, $version)
    {
        $this->pluginName = $pluginName;
        $this->version = $version;
    }

    public function upcomingMatchesWidget()
    {
        require_once ('class-handball-upcoming-matches-widget.php');
        register_widget('HandballUpcomingMatchesWidget');
    }

    public function playedMatchesWidget()
    {
        require_once ('class-handball-played-matches-widget.php');
        register_widget('HandballPlayedMatchesWidget');
    }

    public function addTeamsToMenu($items, $menu)
    {
        if ($menu->slug == 'hauptmenue') {
            $itemTeamId = null;
            foreach ($items as $item) {
                if ($item->url == '/teams') {
                    $itemTeamId = $item->ID;
                }
            }
            if ($itemTeamId == null) {
                return $items;
            }
            $currentSaison = Saison::getCurrentSaison();
            $teams = (new HandballTeamRepository())->findAll($currentSaison->getValue());
            $order = 10000;
            foreach ($teams as $team) {
                $title = $team->getTeamName() . ' ' . $team->getLeagueShort();
                $url = '/teams/' . $team->getTeamId();
                $items[] = self::createCustomNavMenuItem($title, $url, ++ $order, $itemTeamId);
            }
        }
        return $items;
    }

    private static function createCustomNavMenuItem($title, $url, $order, $parent = 0)
    {
        $item = new stdClass();
        $item->ID = 1000000 + $order + $parent;
        $item->db_id = $item->ID;
        $item->title = $title;
        $item->url = $url;
        $item->menu_order = $order;
        $item->menu_item_parent = $parent;
        $item->type = '';
        $item->object = '';
        $item->object_id = '';
        $item->classes = [];
        $item->target = '';
        $item->attr_title = '';
        $item->description = '';
        $item->xfn = '';
        $item->status = '';
        return $item;
    }

    public function teamSite()
    {
    }

    public function postTypeMatch()
    {
        register_post_type('handball_match', [
            'labels' => [
                'name' => __('Berichte'),
                'singular_name' => __('Bericht')
            ],
            'public' => true,
            'has_archive' => true,
            'rewrite'     => ['slug' => 'bericht'],
        ]);
    }

}