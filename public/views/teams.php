<?php get_header(); ?>

<?php
require_once (plugin_dir_path(__FILE__) . '../../includes/class-handball-repository.php');

$teamRepo = new HandballTeamRepository();
$teams = $teamRepo->findAllBySaisonWithPost(Saison::getCurrentSaison());
?>

<div class="wrap">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
  		<h1 class="entry-title">Teams</h1>
		<div class="entry-content clearfix">
    		<?php
    		foreach ($teams as $team) {
    		    echo "<div class='content-column one_half' style='padding-right:25px;'>";
    		    echo '<a href="' . $team->getTeamUrl() . '">';
    		    echo '<h4>' . esc_attr($team->getPostTitle()) . '</h4>';
    		    echo '<img src="'.$team->getFirstImageUrlInPost().'" />';
    		    echo '</a>';
    		    echo '</div>';
    		}
    		?>
		</div>
		</main>
	</div>
</div>

<?php get_footer(); ?>

<style>
#primary {
    width: 100% !important;
}
</style>
