<?php
require_once(plugin_dir_path(__FILE__) . '../includes/class-handball-repository.php');

class HandballMetaBoxTeam
{
    public static function render($post)
    {
        $teamId = get_post_meta($post->ID, 'handball_team_id', true);
        if (empty($teamId) && isset($_GET['handball_team_id'])) {
            $teamId= $_GET['handball_team_id'];
        }
        
        ?>

        <label for="handball_team_id">Team</label>
        <br />
        <select name="handball_team_id" id="handball_team_id" class="postbox" style="width:100%;">
        <?php
            $teamRepo = new HandballTeamRepository();
            foreach ($teamRepo->findAll() as $team) {
                $selected = selected($teamId, $team->getTeamId(), false);
                $value = $team->getTeamId();
                $display = $team->getSaison()->formattedShort() . ' ' . $team->getTeamName() . ' ' . $team->getLeagueLong();
                echo '<option '.$selected.' value="'.esc_attr($value).'">'.esc_attr($display).'</option>';
            }
        ?>
        </select>
		<br />
	   <?php
    }
}

