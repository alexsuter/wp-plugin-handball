<?php get_header(); ?>

<?php
require_once (plugin_dir_path(__FILE__) . '../../includes/class-handball-repository.php');

$repo = new HandballEventRepository();
$upComingEvents = $repo->findUpComingEvents();
$pastEvents = $repo->findPastEvents();

?>

<div class="wrap">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
    		<h1 class="entry-title">Kommende Events</h1>
    		<div class="entry-content clearfix" style="margin-bottom: 20px;">
    		<?php
    		foreach ($upComingEvents as $event) {
    		    include(WP_PLUGIN_DIR . '/handball/public/templates/_event-item.php');
    		}
    		?>
    		</div>

    		<h1 class="entry-title">Vergangene Events</h1>
    		<div class="entry-content clearfix">
    		<?php
    		foreach ($pastEvents as $event) {
    		    include(WP_PLUGIN_DIR . '/handball/public/templates/_event-item.php');
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
