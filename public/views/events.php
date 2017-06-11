<?php get_header(); ?>

<?php
require_once (plugin_dir_path(__FILE__) . '../../includes/class-handball-repository.php');

$repo = new HandballEventRepository();
$events = $repo->findUpComingEvents();
?>

<div class="wrap">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<h1>Events</h1>
		<?php
		foreach ($events as $event) {
		    echo '<p>';
		    echo '<a href="' . $event->getUrl() . '">';
		    echo '<h4>' . esc_attr($event->getTitle()) . '</h4>';
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
