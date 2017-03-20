<?php
if (! current_user_can('manage_options')) { // TODO Create own capability
    return;
}

require_once(plugin_dir_path(__FILE__) . '../../includes/class-handball-team-list.php');

$teamList = new HandballTeamList();
$teamList->prepare_items();

echo '<div class="wrap"><h1>Teams</h1>';
$teamList->display();
echo '</div>';
