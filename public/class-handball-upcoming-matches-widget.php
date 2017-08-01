<?php
require_once (plugin_dir_path(__FILE__) . '../includes/class-handball-repository.php');

class HandballUpcomingMatchesWidget extends WP_Widget
{
    private $matchRepo;

    public function __construct()
    {
        parent::__construct('handball_upcoming_matches_widget', 'Upcoming Matches');
        $this->matchRepo = new HandballMatchRepository();
    }

    public function widget($args, $instance)
    {
        $matches = $this->matchRepo->findMatchesNextWeek();

        $outputs = [];
        foreach ($matches as $match) {
            $output = $match->getGameDateTimeFormattedLong();
            $output .= ' Uhr <br />';
            $output .= $match->getVenue();
            $output .= '<br />';
            $output .= $match->getTeamAName();
            $output .= ' - ';
            $output .= $match->getTeamBName();
            $outputs[] = $output;
        }

        echo $args['before_widget'];
        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }
        echo '<div class="textwidget">';
        if (empty($outputs)) {
            echo 'Keine Spiele in nächster Zeit.';
        } else {
            echo implode('<br /><br />', $outputs);
        }
        echo '</div>';
        echo $args['after_widget'];
    }

    public function form($instance)
    {
        $title = ! empty($instance['title']) ? $instance['title'] : esc_html__('', 'text_domain');
        ?>
<p>
	<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'text_domain' ); ?></label>
	<input class="widefat"
		id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
		name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
		type="text" value="<?php echo esc_attr( $title ); ?>">
</p>
<?php
    }

    public function update($new_instance, $old_instance)
    {
        $instance = [];
        $instance['title'] = (! empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        return $instance;
    }
}