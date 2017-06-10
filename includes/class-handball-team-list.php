<?php
if (! class_exists('WP_List_Table')) {
    require_once (ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

require_once ('class-handball-model.php');
require_once ('class-handball-repository.php');

class HandballTeamList extends WP_List_Table
{

    private $teamRepo;
    private $saisonRepo;
    private $filterSaison;

    public function __construct($args = [])
    {
        parent::__construct($args);
        $this->teamRepo = new HandballTeamRepository();
        $this->saisonRepo = new HandballSaisonRepository();
        $this->filterSaison = Saison::getCurrentSaison()->getValue();
        if (isset($_GET['saison_filter'])) {
            $this->filterSaison = $_GET['saison_filter'];
        }
        ?>
        <style>
        .handball-team-sort-field {
            width:40px;
        }
        </style>
        <script>
        jQuery(document).ready(function($) {
        	$('.handball-team-sort-field, .handball-team-image-id-field').change(function(){
                var value = $(this).val();
                var teamId = $(this).data('team-id');
                var attribute = $(this).data('attribute');
                $.post("/wp-json/handball/teams/" + teamId + "?"+attribute+"=" + value);
            });
        });
        </script>
        <?php
    }

    function get_columns()
    {
        return [
            'sort' => 'Sortierung',
            'image' => 'Bild',
            'team_name' => 'Team',
            'leagues' => 'Liga'
        ];
    }

    function prepare_items()
    {
        $columns = $this->get_columns();
        $hidden = [];
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = [
            $columns,
            $hidden,
            $sortable
        ];

        $this->items = $this->teamRepo->findAll($this->filterSaison);
    }

    function column_default($item, $column_name)
    {
        // TODO
        $imagesQuery = new WP_Query([
            'post_type' => 'attachment',
            'post_mime_type' => 'image',
            'post_status' => 'inherit',
            // 'posts_per_page' => - 1
        ]);

        $imageOptions = [
            '<option value="">Kein Bild</option>'
        ];
        foreach ($imagesQuery->posts as $image) {
            $selected = '';
            if ($image->ID == $item->getImageId()) {
                $selected = 'selected';
            }
            $imageOptions[] = '<option '.$selected.' value="'.$image->ID.'">'.get_the_title($image->ID).'</option>';
        }

        switch ($column_name) {
            case 'team_id':
                return $item->getTeamId();
            case 'sort':
                return '<input data-attribute="sort" data-team-id="'.$item->getTeamId().'" class="handball-team-sort-field" onkeypress="return event.charCode >= 48 && event.charCode <= 57" type="text" value="' . $item->getSort() . '"></input>';
            case 'image':
                return '<select data-attribute="imageId" data-team-id="'.$item->getTeamId().'" class="handball-team-image-id-field">'.implode($imageOptions).'</select>';
            case 'saison':
                return $item->getSaison()->formattedShort();
            case 'team_name':
                $link = $item->getTeamUrl();
                return '<a href="'.$link.'">' . $item->getTeamName() . ' ' . $item->getLeagueShort() . ' (' . $item->getTeamId() . ')</a><br />' . $item->getLeagueLong();
            case 'leagues':
                $output = [];
                foreach ($item->getLeagues() as $league) {
                    $output[] = $league->getGroupText();
                }
                return implode('<br />', $output);
        }
    }

    function extra_tablenav($which)
    {
        if ($which == "top") {
            $saisons = $this->saisonRepo->findAll();
            $url = add_query_arg('saison_filter', '');
            if (!empty($saisons)){
                ?>
                Saison <select name="saison-filter" class="handball-saison-filter">
                    <?php
                    foreach ($saisons as $saison) {
                        $value = $saison->getValue();
                        $selected = selected($this->filterSaison, $saison->getValue(), false);
                        $display = $saison->formattedShort();
                        ?><option value="<?= $value ?>" <?= $selected ?>><?= $display ?></option><?php
                    }
                    ?>
                </select>
                <?php
            }
            ?>
			<script>
            jQuery(document).ready(function($){
            	$('.handball-saison-filter').change(function(){
                    var saison = $(this).val();
                    document.location.href = '<?= $url ?>=' + saison;
                });
            });
            </script>
            <?php
        }
    }

}