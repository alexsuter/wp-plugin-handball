<?php
// $event

echo '<div class="clearfix" style="margin-bottom:10px;">';
    echo '<h2>' . esc_attr($event->formattedStartDateLong()) . '</h2>';

    $imgUrl = $event->getFirstImageUrlInPost();
    if (!empty($imgUrl)) {
        echo '<img src="' . esc_attr($event->getFirstImageUrlInPost()) .'" style="width:400px;float:left;margin-right:10px;" />';
    }

    echo '<b>' . esc_attr($event->getTitle()) . '</b>';
    echo '<br />';
    echo esc_html($event->getExcerpt());

    ?>
    <div class="entry-content clearfix">
	    <a href="<?= $event->getUrl() ?>" class="more-link">Weiterlesen</a>
    </div>
    <?php

echo '</div>';

