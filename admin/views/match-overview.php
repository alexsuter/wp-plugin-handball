<?php
if (! current_user_can('manage_options')) { // TODO Create own capability
    return;
}

require_once(plugin_dir_path(__FILE__) . '../../includes/class-handball-match-list.php');

$matchList = new HandballMatchList();
$matchList->prepare_items();

echo '<div class="wrap"><h1>Spiele</h1>';
$matchList->display();
echo '</div>';
