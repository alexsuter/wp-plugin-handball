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

    public function synchronize() {
        require_once(plugin_dir_path(__FILE__) . '../includes/class-handball-synchronizer.php');

        $apiUrl = get_option('HANDBALL_API_URL');
        $apiUsername = get_option('HANDBALL_API_USERNAME');
        $apiPassword = get_option('HANDBALL_API_PASSWORD');
        $clubId = get_option('HANDBALL_SYNCHRONIZE_CLUB_ID');

        $synchronizer = new HandballSynchronizer($apiUrl, $apiUsername, $apiPassword, $clubId);
        $synchronizer->start();
    }

    public function createSettingsAdmin() {
        add_settings_section('handball_settings_section_synchronize', 'Handball - SHV Synchronisierung', null, 'general');

        register_setting('general', 'HANDBALL_API_URL');
        add_settings_field(
            'handball_setting_api_url',
            'SHV API Url',
            function () {
                $setting = get_option('HANDBALL_API_URL');
                ?><input class="regular-text" type="text" name="HANDBALL_API_URL" value="<?= isset($setting) ? esc_attr($setting) : ''; ?>"><?php
            },
            'general',
            'handball_settings_section_synchronize'
        );

        register_setting('general', 'HANDBALL_API_USERNAME');
        add_settings_field(
            'handball_setting_api_username',
            'SHV API Username',
            function () {
                $setting = get_option('HANDBALL_API_USERNAME');
                ?><input class="regular-text" type="text" name="HANDBALL_API_USERNAME" value="<?= isset($setting) ? esc_attr($setting) : ''; ?>"><?php
                    },
            'general',
            'handball_settings_section_synchronize'
        );

        register_setting('general', 'HANDBALL_API_PASSWORD');
        add_settings_field(
            'handball_setting_api_password',
            'SHV API Password',
            function () {
                $setting = get_option('HANDBALL_API_PASSWORD');
                ?><input class="regular-text" type="text" name="HANDBALL_API_PASSWORD" value="<?= isset($setting) ? esc_attr($setting) : ''; ?>"><?php
                            },
                    'general',
                    'handball_settings_section_synchronize'
                );

        register_setting('general', 'HANDBALL_SYNCHRONIZE_CLUB_ID');
        add_settings_field(
            'handball_setting_api_synchronize_club_id',
            'Club ID to sync',
            function () {
                $setting = get_option('HANDBALL_SYNCHRONIZE_CLUB_ID');
                ?><input class="regular-text" type="text" name="HANDBALL_SYNCHRONIZE_CLUB_ID" value="<?= isset($setting) ? esc_attr($setting) : ''; ?>"><?php
                                    },
                            'general',
                            'handball_settings_section_synchronize'
                        );

    }

    public function createAdminMenu() {
        add_menu_page(
            'Handball',
            'Handball',
            'manage_options', // TODO Create own capability
            'handball_team',
            null,
            'dashicons-awards',
            20
        );

        add_submenu_page(
            'handball_team',
            'Teams',
            'Teams',
            'manage_options',  // TODO Create own capability
            'handball_team',
            function () {
                include(plugin_dir_path(__FILE__) . 'views/team-overview.php');
            }
        );

        add_submenu_page(
            'handball_team',
            'Spiele',
            'Spiele',
            'manage_options',  // TODO Create own capability
            'handball_match',
            function () {
                include(plugin_dir_path(__FILE__) . 'views/match-overview.php');
            }
        );
    }
}