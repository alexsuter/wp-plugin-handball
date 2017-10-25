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
        $output = '';
        $events = $this->eventRepo->findUpComingEvents();
        $events = array_slice($events, 0, 3);
        foreach ($events as $event) {
            $output .= $this->renderEvent($event);
        }
        
        echo $args['before_widget'];
        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }
        echo '<div class="textwidget" style="margin-bottom:0px;">';
        if (empty($output)) {
            echo 'Momentan stehen keine Events an.';
        } else {
            echo $output;
        }
        echo '</div>';
        echo $args['after_widget'];
    }
    
    private function renderEvent(Event $event): string {
        $output = '';
        
        $output .= '<a href="'.$event->getUrl().'">';
        $output .= '<span style="font-weight:bold;font-size:12px;color:#777;">'.esc_attr($event->formattedStartDateLong()).'</span>';
        $output .= '<b style="display:block;margin-bottom:10px;">';
        $output .= esc_attr($event->getTitle());
        $output .= '</b>';
        if (!empty($event->getFirstImageUrlInPost())) {
            $output .= '<img src="'.$event->getFirstImageUrlInPost().'" />';
        }
        $output .= '</a>';
        return $output;
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