<?php get_header(); ?>

<?php
require_once (plugin_dir_path(__FILE__) . '../../includes/class-handball-repository.php');

$teamRepo = new HandballTeamRepository();
$teams = $teamRepo->findAllBySaisonWithPost(Saison::getCurrentSaison());
?>

<div class="wrap">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<?php
		foreach ($teams as $team) {
		    echo '<p>';
		    echo '<a href="' . $team->getTeamUrl() . '">';
		    echo '<h4>' . esc_attr($team->getPostTitle()) . '</h4>';
		    echo '<img src="'.$team->getFirstImageUrlInPost().'" />';
		    echo '</a>';
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
