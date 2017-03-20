<?php
/*
Plugin Name: Handball
Description: This plugin imports all teams and matches over the SHV rest api and keep them up to date.
Author: HCG Dev Team
Version: 0.1
*/

if (!defined('WPINC')) {
	die;
}

function activate_handball_plugin() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-handball-plugin-activator.php';
    HandballPluginActivator::activate();
}
register_activation_hook(__FILE__, 'activate_handball_plugin');

function deactivate_handball_plugin() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-handball-plugin-deactivator.php';
    HandballPluginDeactivator::deactivate();
}
register_deactivation_hook(__FILE__, 'deactivate_handball_plugin');

function run_handball_plugin() {
    require_once(plugin_dir_path(__FILE__) . 'includes/class-handball-plugin.php');
    $plugin = new HandballPlugin();
    $plugin->run();
}
run_handball_plugin();
