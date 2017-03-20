<?php
require_once (plugin_dir_path(__FILE__) . '../includes/class-handball-repository.php');

class HandballUpcomingMatchesWidget extends WP_Widget
{
    private $matchRepo;

    public function __construct()
    {
        parent::__construct('handball_upcoming_matches_widget', 'Upcoming Matches');
        $this->matchRepo = new HandballMatchRepository();
    }

    public function widget($args, $instance)
    {
        $matches = $this->matchRepo->findMatchesNextWeek();

        $outputs = [];
        foreach ($matches as $match) {
            $output = mysql2date('d.m.Y h:i', $match->getGameDateTime());
            $output .= ' Uhr <br />';
            $output .= $match->getVenue();
            $output .= '<br />';
            $output .= $match->getTeamAName();
            $output .= ' - ';
            $output .= $match->getTeamBName();
            $outputs[] = $output;
        }

        echo '<h2 class="widget-title">Spielplan</h2>';
        echo implode('<br /><br />', $outputs);
    }

    public function form($instance)
    {}

    public function update($new_instance, $old_instance)
    {}
}