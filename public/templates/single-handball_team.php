<?php
get_header(); ?>

	<section id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php while ( have_posts() ) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        	<header class="entry-header">
        		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
        	</header>
        	<div class="entry-content clearfix">
        		<?php the_content(); ?>
        	</div>
        </article>
        <?php




			global $post;

			require_once (plugin_dir_path(__FILE__) . '../../includes/class-handball-repository.php');

			$teamId = get_post_meta($post->ID, 'handball_team_id', true);



			//$teamRepo = new HandballTeamRepository();
			//$team = $teamRepo->findById($teamId);

			$matchRepo = new HandballMatchRepository();
			$matches = $matchRepo->findMatchesForTeam($teamId);

			if (!empty($matches)) {
			    echo '<h2>Spiele</h2>';
			}

			foreach($matches as $match) {
			?>
				<div style="text-align:center;border-bottom:0px solid #eee;padding-top:15px;padding-bottom:15px;">
					<?= $match->getGameDateTimeFormattedShort() ?> Uhr in <?= $match->getVenue() ?>
					<br />
					<span style="font-size:20px;"><?= $match->getEncounter() ?></span>
					<br />
					<?= $match->getScore()?>

					<?php
					    $previewUrl = $match->getPostPreviewUrl();
					    if ($previewUrl!= null) {
                            echo '<br /><a href="'.$previewUrl.'">Vorschau</a>';
                        }
                        $reportUrl = $match->getPostReportUrl();
                        if ($reportUrl != null) {
                            if ($previewUrl == null) {
                                echo '<br />';
                            } else {
                                echo ' | ';
                            }
                            echo '<a href="'.$reportUrl.'">Bericht</a>';
                        }
				    ?>
				</div>

			<?php } endwhile; ?>


			<?php
			$groupRepo = new HandballGroupRepository();
			$groups = $groupRepo->findGroupsByTeamId($teamId);

			if (!empty($groups)) {
			    echo '<h2>Ranglisten</h2>';
			}

			foreach ($groups as $group) {
                ?>
                <div >
                	<h4><?= $group->getLeagueLong() ?></h4>

                	<table>
                		<tr>
                			<th style="width:35px;"></th>
							<th style="width:300px;"></th>
							<th>Pkt.</th>
							<th>Spiele</th>
							<th>Siege</th>
							<th>Nied.</th>
							<th>Unent.</th>
							<th>T+</th>
							<th>T-</th>
							<th>TD</th>
                		</tr>
                	<?php
                	   foreach ($group->getRankings() as $ranking) {
                	       ?>
                	       <tr>
                	       	<td class="td-ranking"><?= $ranking->getRank(); ?></td>
                	       	<td><?= $ranking->getTeamName(); ?></td>
                	       	<td class="td-ranking"><?= $ranking->getTotalPoints() ?></td>
                	       	<td class="td-ranking"><?= $ranking->getTotalGames() ?></td>
                	       	<td class="td-ranking"><?= $ranking->getTotalWins(); ?></td>
                	       	<td class="td-ranking"><?= $ranking->getTotalLoss(); ?></td>
                	       	<td class="td-ranking"><?= $ranking->getTotalDraws(); ?></td>
                	       	<td class="td-ranking"><?= $ranking->getTotalScoresPlus(); ?></td>
                	       	<td class="td-ranking"><?= $ranking->getTotalScoresMinus(); ?></td>
                	       	<td class="td-ranking"><?= $ranking->getTotalScoresDiff(); ?></td>
                	       </tr>
                	       <?php
                	   }
                    ?>
                    </table>
                    <style>
                    .td-ranking {
                         text-align:center;
                    }
                    </style>
                </div>
                <?php
			}
			?>
		</main>
	</section>

	<?php // get_sidebar(); ?>

<?php get_footer(); ?>


<style>
#primary {
    width: 100% !important;
}
</style>