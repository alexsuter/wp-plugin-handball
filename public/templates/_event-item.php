<?php
// $event

echo '<div class="clearfix" style="margin-bottom:10px;">';
    echo '<b>' . esc_attr($event->formattedStartDateLong()) . '</b>';
    echo '&ensp; <a href="' . $event->getUrl() . '">';
        echo esc_attr($event->getTitle());
    echo '</a><br />';

    $imgUrl = $event->getFirstImageUrlInPost();
    if (!empty($imgUrl)) {
        echo '<img src="' . esc_attr($event->getFirstImageUrlInPost()) .'" style="width:400px;float:left;margin-right:10px;" />';
    }

    echo esc_html($event->getExcerpt());

echo '</div>';

