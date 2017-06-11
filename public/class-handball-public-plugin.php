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

    public function registerPostTypeMatch()
    {
        register_post_type('handball_match', [
            'labels' => [
                'name' => __('Berichte'),
                'singular_name' => __('Bericht')
            ],
            'public' => true,
            'has_archive' => true,
            'rewrite' => [
                'slug' => 'bericht'
            ]
        ]);
    }

    public function registerPostTypeTeam()
    {
        register_post_type('handball_team', [
            'labels' => [
                'name' => __('Teams'),
                'singular_name' => __('Team')
            ],
            'public' => true,
            'has_archive' => true,
            'rewrite' => [
                'slug' => 'team'
            ]
        ]);
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

    public function addSingleTeamTemplate($singleTemplate)
    {
        global $post;
        $file = PLUGINDIR . '/handball/public/templates/single-' . $post->post_type . '.php';
        if (file_exists($file)) {
            return $file;
        }
        return $singleTemplate;
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
            $teams = (new HandballTeamRepository())->findAllBySaisonWithPost(Saison::getCurrentSaison());
            $order = 10000;
            foreach ($teams as $team) {
                $title = esc_attr($team->getPostTitle());
                $url = $team->getTeamUrl();
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

}