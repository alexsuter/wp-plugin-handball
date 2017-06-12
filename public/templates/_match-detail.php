<?php
// $match:Match
// $showScore:boolean
// $showLinks:boolean
?>

<div class="entry-content clearfix" style="text-align:center;border-bottom:0px solid #eee;padding-top:15px;padding-bottom:15px; ">
	<?= $match->getGameDateTimeFormattedShort() ?> Uhr in <?= $match->getVenue() ?>

	<br />

	<span style="font-size:20px;"><?= $match->getEncounter() ?></span>

	<?php if ($showScore) { ?>
		<br />
		<?= $match->getScore() ?>
	<?php } ?>

	<?php
    if ($showLinks) {
        $previewUrl = $match->getPostPreviewUrl();
        if ($previewUrl != null) {
            echo '<br /><a href="' . $previewUrl . '">Vorschau</a>';
        }
        $reportUrl = $match->getPostReportUrl();
        if ($reportUrl != null) {
            if ($previewUrl == null) {
                echo '<br />';
            } else {
                echo ' | ';
            }
            echo '<a href="' . $reportUrl . '">Bericht</a>';
        }
    }
    ?>

</div>
