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
}