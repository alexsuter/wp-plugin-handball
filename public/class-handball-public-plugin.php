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

    public function taxonomyMatchPostType()
    {
        /*$labels = [
            'name'              => _x('Courses', 'taxonomy general name'),
            'singular_name'     => _x('Course', 'taxonomy singular name'),
            'search_items'      => __('Search Courses'),
            'all_items'         => __('All Courses'),
            'parent_item'       => __('Parent Course'),
            'parent_item_colon' => __('Parent Course:'),
            'edit_item'         => __('Edit Course'),
            'update_item'       => __('Update Course'),
            'add_new_item'      => __('Add New Course'),
            'new_item_name'     => __('New Course Name'),
            'menu_name'         => __('Course'),
        ];
        $args = [
            'hierarchical'      => false,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => ['slug' => 'course'],
        ];
        register_taxonomy('match_post_type', ['handball_match'], $args);

        wp_insert_term(
            'Game Preview', // the term
            'match_post_type', // the taxonomy
            array(
                'slug' => 'game-preview',
            )
            );

        wp_insert_term(
            'Game Report', // the term
            'match_post_type', // the taxonomy
            array(
                'slug' => 'game-report',
            )
            );*/
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