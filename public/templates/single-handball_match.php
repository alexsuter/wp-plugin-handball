<?php
get_header(); ?>

	<section id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php while ( have_posts() ) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        	<header class="entry-header">
        		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
        	</header>

        	<?php
    			global $post;
    			require_once (plugin_dir_path(__FILE__) . '../../includes/class-handball-repository.php');
    			$matchRepo = new HandballMatchRepository();
    			$matchId = get_post_meta($post->ID, 'handball_game_id', true);
    			$gameReportType = get_post_meta($post->ID, 'handball_game_report_type', true);

    			$match = $matchRepo->findById($matchId);
    			$showScore = $gameReportType == 'report';
    			$showLinks = false;
    			include '_match-detail.php';

    			the_content();
			?>

        </article>
        <?php endwhile;?>

		</main>
	</section>

	<?php // get_sidebar(); ?>

<?php get_footer(); ?>


<style>
#primary {
    width: 100% !important;
}
.responsive-table-container
{
	width: 100%;
	overflow-y: auto;
	_overflow: auto;
	margin: 0 0 1em;
}
</style>
