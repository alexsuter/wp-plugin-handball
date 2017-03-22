<?php
if (! current_user_can('manage_options')) { // TODO Create own capability
    return;
}

require_once(plugin_dir_path(__FILE__) . '../../includes/class-handball-team-list.php');

$teamList = new HandballTeamList();
$teamList->prepare_items();
?>

<div class="wrap">
	<h1>Teams</h1>
	<?= $teamList->display() ?>
</div>

<style type="text/css">
    .column-saison { width:50px !important; overflow:hidden }
</style>
