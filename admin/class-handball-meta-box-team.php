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

        $sort = get_post_meta($post->ID, 'handball_team_sort', true);
        if (empty($sort) && isset($_GET['handball_team_sort'])) {
            $sort= $_GET['handball_team_sort'];
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
                $display = $team->getTeamName();
                echo '<option '.$selected.' value="'.$value.'">'.$display.'</option>';
            }
        ?>
        </select>

        <br />

        <label for="handball_team_sort">Sortierungsnummer</label>
        <br />
        <input name="handball_team_sort" id="handball_team_sort" class="postbox" type="text" value="<?= $sort ?>"></input>
        <?php
    }
}

