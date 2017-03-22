<?php
if (! current_user_can('manage_options')) { // TODO Create own capability
    return;
}

require_once(plugin_dir_path(__FILE__) . '../../includes/class-handball-match-list.php');

echo '<div class="wrap"><h1>Spiele</h1>';

echo '<h2>Vergangene Spiele</h2>';
$matchList = new HandballMatchList();
$matchList->prepare_items(false, true);
$matchList->display();

echo '<h2>Kommende Spiele</h2>';
$matchList = new HandballMatchList();
$matchList->prepare_items(true, false);
$matchList->display();

echo '<h2>Alle Spiele</h2>';
$matchList = new HandballMatchList();
$matchList->prepare_items(false, false);
$matchList->display();
echo '</div>';
