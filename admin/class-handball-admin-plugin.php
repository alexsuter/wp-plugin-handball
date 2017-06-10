<?php

class HandballAdminPlugin
{

    private $pluginName;

    private $version;

    public function __construct($pluginName, $version)
    {
        $this->pluginName = $pluginName;
        $this->version = $version;
    }

    public function synchronize() {
        require_once(plugin_dir_path(__FILE__) . '../includes/class-handball-synchronizer.php');

        $apiUrl = get_option('HANDBALL_API_URL');
        $apiUsername = get_option('HANDBALL_API_USERNAME');
        $apiPassword = get_option('HANDBALL_API_PASSWORD');
        $clubId = get_option('HANDBALL_SYNCHRONIZE_CLUB_ID');

        $synchronizer = new HandballSynchronizer($apiUrl, $apiUsername, $apiPassword, $clubId);
        $synchronizer->start();
    }

    public function metaBoxMatch()
    {
        function html($post) {
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
            <?php
        }
        add_meta_box('handball_metabox_match', 'Handball', 'html', 'handball_match');
    }

    public function savePostdata($postId)
    {
        if (array_key_exists('handball_game_id', $_POST)) {
            $id = $_POST['handball_game_id'];
            if (intval($id)) {
                update_post_meta($postId, 'handball_game_id', $id);
            }
        }
        if (array_key_exists('handball_game_report_type', $_POST)) {
            update_post_meta($postId, 'handball_game_report_type', $_POST['handball_game_report_type']);
        }
    }

    public function createSettingsAdmin() {
        add_settings_section('handball_settings_section_synchronize', 'Handball - SHV Synchronisierung', null, 'general');

        register_setting('general', 'HANDBALL_API_URL');
        add_settings_field(
            'handball_setting_api_url',
            'SHV API Url',
            function () {
                $setting = get_option('HANDBALL_API_URL');
                ?><input class="regular-text" type="text" name="HANDBALL_API_URL" value="<?= isset($setting) ? esc_attr($setting) : ''; ?>"><?php
            },
            'general',
            'handball_settings_section_synchronize'
        );

        register_setting('general', 'HANDBALL_API_USERNAME');
        add_settings_field(
            'handball_setting_api_username',
            'SHV API Username',
            function () {
                $setting = get_option('HANDBALL_API_USERNAME');
                ?><input class="regular-text" type="text" name="HANDBALL_API_USERNAME" value="<?= isset($setting) ? esc_attr($setting) : ''; ?>"><?php
                    },
            'general',
            'handball_settings_section_synchronize'
        );

        register_setting('general', 'HANDBALL_API_PASSWORD');
        add_settings_field(
            'handball_setting_api_password',
            'SHV API Password',
            function () {
                $setting = get_option('HANDBALL_API_PASSWORD');
                ?><input class="regular-text" type="text" name="HANDBALL_API_PASSWORD" value="<?= isset($setting) ? esc_attr($setting) : ''; ?>"><?php
                            },
                    'general',
                    'handball_settings_section_synchronize'
                );

        register_setting('general', 'HANDBALL_SYNCHRONIZE_CLUB_ID');
        add_settings_field(
            'handball_setting_api_synchronize_club_id',
            'Club ID to sync',
            function () {
                $setting = get_option('HANDBALL_SYNCHRONIZE_CLUB_ID');
                ?><input class="regular-text" type="text" name="HANDBALL_SYNCHRONIZE_CLUB_ID" value="<?= isset($setting) ? esc_attr($setting) : ''; ?>"><?php
                                    },
                            'general',
                            'handball_settings_section_synchronize'
                        );

    }

    public function createAdminMenu() {
        $capability = 'manage_options'; // TODO Create own capability

        add_menu_page(
            'Handball',
            'Handball',
            $capability,
            'handball_match',
            null,
            'dashicons-awards',
            20
        );

        add_submenu_page(
            'handball_match',
            'Spiele',
            'Spiele',
            $capability,
            'handball_match',
            function () {
                include(plugin_dir_path(__FILE__) . 'views/match-overview.php');
            }
        );

        add_submenu_page(
            'handball_match',
            'Teams',
            'Teams',
            $capability,
            'handball_team',
            function () {
                include(plugin_dir_path(__FILE__) . 'views/team-overview.php');
            }
        );
    }

    public function initRestApis() {
        register_rest_route('handball', '/teams/(?P<teamId>\d+)', [
            'methods' => 'POST',
            'callback' => 'handballUpdateTeam',
        ]);

        function handballUpdateTeam(WP_REST_Request $request) {
            $teamId = $request->get_param('teamId');

            $repo = new HandballTeamRepository();
            $team = $repo->findById($teamId);

            if (isset($request->get_params()['sort'])) {
                $team->setSort($request->get_param('sort'));
            }

            if (isset($request->get_params()['imageId'])) {
                $team->setImageId($request->get_param('imageId'));
            }

            $repo->saveTeam($team);

            return 1;
        }
    }
}