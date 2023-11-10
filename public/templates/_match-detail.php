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
$highlightHomeGameCSS = 'background-color:rgba(0, 61, 102, 0.1);';
$homegameHeader = '<b>AUSWÄRTSSPIEL</b>';
if (isset($highlightHomeGame) && $highlightHomeGame) {
    if ($match->isHomeGame()) {
        //$highlightHomeGameCSS = 'background-color:rgba(242, 128, 0, 0.1);';
        $homegameHeader = '<b>HEIMSPIEL</b>';
    }
}
?>

<div class="entry-content clearfix" style="text-align:center;border-bottom:0px solid #eee;margin-bottom:5px;padding-top:20px;padding-bottom:15px;<?= $highlightHomeGameCSS ?>">

    <?php 
      if (!$match->isPlayed()) {
        ?>
          <div style="margin-bottom:10px;">
            <?= $homegameHeader ?>
          </div>
        <?php
      }
    ?>

	<?php
	   if ($showLeague) {
	       echo $match->getLeagueLong();
	       echo '<br />';
	   }
	?>

    <img style="position:relative;right:15px;" src="<?= $match->getTeamAImageUrl(60) ?>" />
    <span style="position:relative;bottom:20px;font-weight:normal;font-size:30px;">-</span>
    <img style="position:relative;left:15px;" src="<?= $match->getTeamBImageUrl(60) ?>" />
    <br />

	<span style="font-size:20px;">
		<?php
		if ($showEncounterWithLeague) {
		    echo $match->getEncounterWithLeague();
		} else {
		    echo $match->getEncounter();
		}
		?>
	</span>

    <br />

    <?php
     if (!$match->isPlayed()) {
        echo $match->getGameDateTimeFormattedShort(); 
        echo " Uhr in ";
        echo $match->getVenue();
     } else {
       echo $match->getScore();
     }
    ?>
	<br />

	<?php
//	if ($showScore) {
//    }

/*
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
    */
    ?>

</div>
