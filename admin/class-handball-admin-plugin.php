<?php

class HandballAdminPlugin
{

    private $pluginName;

    private $version;

    public function __construct($pluginName, $version)
    {
        $this->pluginName = $pluginName;
        $this->version = $version;
    }

    public function synchronize()
    {
        require_once (plugin_dir_path(__FILE__) . '../includes/class-handball-shv-synchronizer.php');

        $apiUrl = get_option('HANDBALL_API_URL');
        $apiUsername = get_option('HANDBALL_API_USERNAME');
        $apiPassword = get_option('HANDBALL_API_PASSWORD');
        $clubId = get_option('HANDBALL_SYNCHRONIZE_CLUB_ID');

        $synchronizer = new HandballShvSynchronizer($apiUrl, $apiUsername, $apiPassword, $clubId);
        $synchronizer->start();
    }

    public function addMetaBoxForPostTypeMatch()
    {
        require_once('class-handball-meta-box-match.php');
        add_meta_box('handball_metabox_match', 'Handball - Match', 'HandballMetaBoxMatch::render', 'handball_match');
    }

    public function addMetaBoxForPostTypeTeam()
    {
        require_once('class-handball-meta-box-team.php');
        add_meta_box('handball_metabox_team', 'Handball - Team', 'HandballMetaBoxTeam::render', 'handball_team');
    }

    public function savePostMetaForMatch($postId)
    {
        $gameIdKey = 'handball_game_id';
        if (array_key_exists($gameIdKey, $_POST)) {
            $id = $_POST[$gameIdKey];
            if (intval($id)) {
                update_post_meta($postId, $gameIdKey, $id);
            }
        }
        $reportTypeKey = 'handball_game_report_type';
        if (array_key_exists($reportTypeKey, $_POST)) {
            update_post_meta($postId, $reportTypeKey, $_POST[$reportTypeKey]);
        }
    }

    public function savePostMetaForTeam($postId)
    {
        $teamIdKey = 'handball_team_id';
        if (array_key_exists($teamIdKey, $_POST)) {
            $id = $_POST[$teamIdKey];
            if (intval($id)) {
                update_post_meta($postId, $teamIdKey, $id);
            }
        }
        $sortKey = 'handball_team_sort';
        if (array_key_exists($sortKey, $_POST)) {
            update_post_meta($postId, $sortKey, $_POST[$sortKey]);
        }
    }

    public function createSettingsAdmin() {
        require_once('class-handball-settings.php');
        HandballSettings::registerSettings();
    }

    public function createAdminMenu() {
        $capability = 'manage_options'; // TODO Create own capability

        add_menu_page(
            'Handball',
            'Handball',
            $capability,
            'handball_match',
            null,
            'dashicons-awards',
            20
        );

        add_submenu_page(
            'handball_match',
            'Spiele',
            'Spiele',
            $capability,
            'handball_match',
            function () {
                include(plugin_dir_path(__FILE__) . 'views/match-overview.php');
            }
        );

        add_submenu_page(
            'handball_match',
            'Teams',
            'Teams',
            $capability,
            'handball_team',
            function () {
                include(plugin_dir_path(__FILE__) . 'views/team-overview.php');
            }
        );
    }

}