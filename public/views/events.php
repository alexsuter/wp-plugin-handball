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
		<h1>Kommende Events</h1>
		<?php
		foreach ($upComingEvents as $event) {
		    echo '<p>';
		    echo $event->formattedStartDateLong();
		    echo ' <a href="' . $event->getUrl() . '">';
		    echo esc_attr($event->getTitle());
		    echo '</a>';
		    echo '</p>';
		}
		?>

		<h1>Vergangene Events</h1>
		<?php
		foreach ($pastEvents as $event) {
		    echo '<p>';
		    echo $event->formattedStartDateLong();
		    echo '<a href="' . $event->getUrl() . '">';
		    echo ' ' . esc_attr($event->getTitle());
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
