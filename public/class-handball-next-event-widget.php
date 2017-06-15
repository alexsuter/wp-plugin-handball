<?php
require_once (plugin_dir_path(__FILE__) . '../includes/class-handball-repository.php');

class HandballNextEventWidget extends WP_Widget
{
    private $eventRepo;

    public function __construct()
    {
        parent::__construct('handball_next_event_widget', 'Next Event');
        $this->eventRepo= new HandballEventRepository();
    }

    public function widget($args, $instance)
    {
        $event= $this->eventRepo->findNextEvent();

        $output = '';
        if ($event != null) {
            $output .= '<h4>' . esc_attr($event->formattedStartDateLong()) . ' ' . esc_attr($event->getTitle()) . '</h4>';
            $output .= '<a href="'.$event->getUrl().'">';
            $output .= '<img src="'.$event->getFirstImageUrlInPost().'" />';
            $output .= '</a>';
        }

        echo $args['before_widget'];
        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }
        echo '<div class="textwidget">';
        if (empty($output)) {
            echo 'Momentan stehen keine Events an.';
        } else {
            echo $output;
        }
        echo '</div>';
        echo $args['after_widget'];
    }

    public function form($instance)
    {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( '', 'text_domain' );
        ?>
        <p>
        	<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'text_domain' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <?php
    }

    public function update($new_instance, $old_instance)
    {
        $instance = [];
        $instance['title'] = ( !empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        return $instance;
    }
}