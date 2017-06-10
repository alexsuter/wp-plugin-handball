<?php get_header(); ?>

<?php
require_once (plugin_dir_path(__FILE__) . '../../includes/class-handball-repository.php');

$GET_VARIABLE_TEAM_ID = get_query_var('team');

$matchRepo = new HandballMatchRepository();
$matches = $matchRepo->findMatchesForTeam($GET_VARIABLE_TEAM_ID);

$teamRepo = new HandballTeamRepository();
$team = $teamRepo->findById($GET_VARIABLE_TEAM_ID);
?>

<div class="wrap">
<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">

		<h2><?= $team->getTeamName() ?></h2>
		<h4><?= $team->getLeagueLong() ?></h4>

		<?php if ($team->hasImage()) { ?>

			<img src="<?= $team->getImageUrl() ?>" />
			<p> <?= nl2br(get_post($team->getImageId())->post_content) ?> </p>
		<?php } ?>

	<table class="wp-list-table widefat fixed striped">
		<thead>
			<tr>
				<th>Datum</th>
				<th>Liga</th>
				<th>Begegnung</th>
				<th>Ort</th>
				<th></th>
		</thead>
		<tbody>
			<?php foreach($matches as $match) { ?>
			<tr>
				<td><?= $match->getGameDateTimeFormattedShort() ?></td>
				<td><?= $match->getLeagueShort() ?></td>
				<td><center><?= $match->getEncounter()?><br /><?= $match->getScore()?></center></td>
				<td><?= $match->getVenue() ?></td>
				<td>
					<?php
					    $previewUrl = $match->getPostPreviewUrl();
					    if ($previewUrl!= null) {
                            echo '<a href="'.$previewUrl.'">Vorschau</a>';
                        }
                        $reportUrl = $match->getPostReportUrl();
                        if ($reportUrl!= null) {
                            if ($previewUrl != null) {
                                echo '<br />';
                            }
                            echo '<a href="'.$reportUrl.'">Bericht</a>';
                        }
				    ?>
				</td>
			</tr>
			<?php } ?>
		</tbody>
	</table>

    </main>
</div>
</div>
<?php get_footer(); ?>

<style>
#primary {
    width: 100% !important;
}
</style>
