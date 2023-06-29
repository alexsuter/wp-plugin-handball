<?php
// $match:Game
// $showScore:boolean
// $showPreviewLink:boolean
// $showReportLink:boolean
// $showLeague:boolean
// $showEncounterWithLeague:boolean
// $highlightHomeGame:boolean
?>

<?php 
$highlightHomeGameCSS = '';
$homegameHeader = '';
if (isset($highlightHomeGame) && $highlightHomeGame) {
    if ($match->isHomeGame()) {
        $highlightHomeGameCSS = 'background-color:black;color:white;';
        $homegameHeader = '<b>HEIMSPIEL</b> <br />';
    }
}
?>

<div class="entry-content clearfix" style="text-align:center;border-bottom:0px solid #eee;padding-top:15px;padding-bottom:15px;<?= $highlightHomeGameCSS ?>">
	
	<?= $homegameHeader ?>
	
	<?= $match->getGameDateTimeFormattedShort() ?> Uhr in <?= $match->getVenue() ?>

	<br />

	<?php
	   if ($showLeague) {
	       echo $match->getLeagueLong();
	       echo '<br />';
	   }
	?>

	<span style="font-size:20px;">
		<?php
		if ($showEncounterWithLeague) {
		    echo $match->getEncounterWithLeague();
		} else {
		    echo $match->getEncounter();
		}
		?>
	</span>

	<?php
	if ($showScore) {
        echo '<br />';
        echo $match->getScore();
    }

	 if ($showPreviewLink || $showReportLink) {

        $previewUrl = $match->getPostPreviewUrl();
        $reportUrl = $match->getPostReportUrl();

        if (!empty($previewUrl) || !empty($reportUrl)) {
            echo '<br />';
        }

        if ($showPreviewLink) {
            if (!empty($previewUrl)) {
                echo '<a href="' . $previewUrl . '">Vorschau</a>';
            }
        }

        if ($showPreviewLink && $showReportLink && !empty($previewUrl) && !empty($reportUrl)) {
            echo ' | ';
        }

        if ($showReportLink) {
            if (!empty($reportUrl)) {
                echo '<a href="' . $reportUrl. '">Bericht</a>';
            }
        }
    }
    ?>

</div>
