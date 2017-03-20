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
    }

    private function definePublicHocks()
    {
        $publicPlugin = new HandballPublicPlugin($this->getPluginName(), $this->getVersion());
        $this->loader->add_action('widgets_init', $publicPlugin, 'upcomingMatchesWidget');
        $this->loader->add_action('widgets_init', $publicPlugin, 'playedMatchesWidget');
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