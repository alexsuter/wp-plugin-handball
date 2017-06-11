<?php get_header(); ?>

<?php
require_once (plugin_dir_path(__FILE__) . '../../includes/class-handball-repository.php');

$teamRepo = new HandballTeamRepository();
$teams = $teamRepo->findAllBySaison(Saison::getCurrentSaison());
?>

<div class="wrap">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<?php
		foreach ($teams as $team) {
		    echo '<p>';
		    echo '<h4><a href="' . $team->getTeamUrl() . '">' . $team->getTeamName() . ' ' . $team->getLeagueShort() . '</a></h4>';
		    //if ($team->hasImage()) {
		      //  echo '<img style="width:250px;" src="' . $team->getImageUrl() . '" />';
		    //}
		    echo '</p>';
		}
		?>
		</main>
	</div>
</div>

<?php get_footer(); ?>

<style>
#primary {
    width: 100% !important;
}
</style>
