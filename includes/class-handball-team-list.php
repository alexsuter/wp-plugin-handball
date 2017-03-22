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

        $this->filterSaison = '20162017'; // TODO current sasion
        if (isset($_GET['saison_filter'])) {
            $this->filterSaison = $_GET['saison_filter'];
        }
    }

    function get_columns()
    {
        return [
            'saison' => 'Saison',
            'team_name' => 'SHV Team Name',
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
        switch ($column_name) {
            case 'team_id':
                return $item->getTeamId();
            case 'saison':
                return $item->getSaison()->formattedShort();
            case 'team_name':
                return $item->getTeamName() . ' (' . $item->getTeamId() . ')';
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
                    // TODO link
                    document.location.href = 'admin.php?page=handball_team&saison_filter=' + saison;
                });
            });
            </script>
            <?php
        }
    }

}