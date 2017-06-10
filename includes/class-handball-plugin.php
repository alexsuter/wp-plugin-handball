<?php

class HandballPlugin
{

    private $loader;

    private $pluginName;

    private $version;

    public function __construct()
    {
        $this->pluginName = 'hcg-match';
        $this->version = '0.0.1';
        $this->loadDependencies();
        $this->setLocale();
        $this->defineAdminHocks();
        $this->definePublicHocks();
    }

    public function run()
    {
        $this->loader->run();
    }

    private function loadDependencies()
    {
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-handball-plugin-loader.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-handball-admin-plugin.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-handball-public-plugin.php';
        $this->loader = new HandballPluginLoader();
    }

    private function setLocale()
    {}

    private function defineAdminHocks()
    {
        $adminPlugin = new HandballAdminPlugin($this->getPluginName(), $this->getVersion());
        $this->loader->add_action('admin_menu', $adminPlugin, 'createAdminMenu');
        $this->loader->add_action('handball_synchronize_data', $adminPlugin, 'synchronize');
        $this->loader->add_action('admin_init', $adminPlugin, 'createSettingsAdmin');
        $this->loader->add_action('add_meta_boxes', $adminPlugin, 'metaBoxMatch');
        $this->loader->add_action('save_post', $adminPlugin, 'savePostdata');
        $this->loader->add_action('rest_api_init', $adminPlugin, 'initRestApis');
    }

    private function definePublicHocks()
    {
        $publicPlugin = new HandballPublicPlugin($this->getPluginName(), $this->getVersion());
        $this->loader->add_action('widgets_init', $publicPlugin, 'upcomingMatchesWidget');
        $this->loader->add_action('widgets_init', $publicPlugin, 'playedMatchesWidget');
        $this->loader->add_action('init', $publicPlugin, 'postTypeMatch');
        $this->loader->add_action('init', $publicPlugin, 'teamSite');
        $this->loader->add_filter('wp_get_nav_menu_items', $publicPlugin, 'addTeamsToMenu', 20, 2);

        // CUSTOM TEAM TEMPLATE!
        function plugin_rewrite_rule_team(){
            add_rewrite_rule('^teams$', 'index.php?team=all', 'top');
            add_rewrite_rule('^teams/([^/]*)$', 'index.php?team=$matches[1]', 'top');
            flush_rewrite_rules(true);
        }
        add_action('init', 'plugin_rewrite_rule_team');

        function plugin_query_vars_team($vars) {
            array_push($vars, 'team');
            return $vars;
        }
        add_action('query_vars', 'plugin_query_vars_team');

        function plugin_template_include_team($template) {
            $queryVar = get_query_var('team');
            if ($queryVar) {
                if ($queryVar == 'all') {
                    $template = WP_PLUGIN_DIR . '/handball/public/views/teams.php';
                } else {
                    $template = WP_PLUGIN_DIR . '/handball/public/views/team.php';
                }
            }
            return $template;
        }
        add_filter('template_include', 'plugin_template_include_team');
    }

    private function getPluginName()
    {
        return $this->pluginName;
    }

    private function getVersion()
    {
        return $this->version;
    }
}