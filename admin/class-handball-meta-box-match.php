<?php

class HandballMetaBoxMatch
{
    public static function render($post)
    {
        require_once(plugin_dir_path(__FILE__) . '../includes/class-handball-repository.php');
        $matchRepo = new HandballMatchRepository();
        $matches = $matchRepo->findAll();
        
        $handballGameId = get_post_meta($post->ID, 'handball_game_id', true);
        if (empty($handballGameId) && isset($_GET['handball_game_id'])) {
            $handballGameId = $_GET['handball_game_id'];
        }
        $handballGameReportType = get_post_meta($post->ID, 'handball_game_report_type', true);
        if (empty($handballGameReportType) && isset($_GET['handball_game_report_type'])) {
            $handballGameReportType = $_GET['handball_game_report_type'];
        }
        
        $isNewsKey = 'handball_is_news';
        $isNews = get_post_meta($post->ID, $isNewsKey, true);
        if (empty($isNews)) {
            $isNews = false;
        }
        ?>
        <label for="handball_game_report_type">Typ</label>
        <br />
        <select name="handball_game_report_type" style="width:100%;" id="handball_game_report_type" aria-required="true" class="postbox">
            <option <?= selected($handballGameReportType, 'preview', false) ?> value="preview">Vorschau</option>
            <option <?= selected($handballGameReportType, 'report', false) ?> value="report">Bericht</option>
        </select>
        <br />
        
        <label for="handball_game_id">Match</label>
        <br />
        <select name="handball_game_id" id="handball_game_id" class="postbox" style="width:100%;">
        <?php
            foreach ($matches as $match) {
                $selected = selected($handballGameId, $match->getGameId(), false);
                $value = $match->getGameId();
                $display = $match->getGameDateTimeFormattedShort() . ' ' . $match->getLeagueShort() . ' ' . $match->getTeamAName() . ' - ' . $match->getTeamBName();
                echo '<option '.$selected.' value="'.$value.'">'.$display.'</option>';
            }
        ?>
        </select>
        
        <label for="handball_is_news">Als News anzeigen</label>
        <br />
        <?php 
            $checked = $isNews ? 'checked' : '';
        ?>
        <input type="checkbox" name="handball_is_news" id="handball_is_news" <?= $checked ?>/>
        <?php
    }
}

