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

    private function getNumberOfEvents() {
		return get_option('HANDBALL_NUMBER_OF_EVENTS_TO_SHOW', 3);
	}
	
    public function widget($args, $instance)
    {
        $output = '';
		$events = $this->eventRepo->findUpComingEvents();
		$events = array_slice($events, 0, $this->getNumberOfEvents());
		foreach ($events as $event) {
			$output .= $this->renderEvent($event);
		}
        
        echo $args['before_widget'];
        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }
        if (empty($output)) {
            echo 'Momentan stehen keine Events an.';
        } else {
            echo $output;
        }        
        echo $args['after_widget'];
    }
    
    private function renderEvent(Event $event): string {
        $output = '';
        
        //$output .= '<a href="'.$event->getUrl().'">';

        $hidden = "visibility:hidden;";
        if ($event->hasContent()) {
            $hidden = '';
        }

        $output .= "
        
        <div style='margin-bottom:10px;'>
            <div class='background-orange' style='width:50px;height:60px;text-align:center;padding-top:8px;float:left;'>
                <div style='font-size:24px;font-weight:bold;color:white;'>
                    <span>".esc_attr($event->getDay())."</span>
                </div>
                <div style='font-size:14px;font-weight:bold;color:white;text-transform:uppercase;'>
                    <span style='font-size:14px;font-weight:bold;color:white;'>".esc_attr($event->getMonth())."</span>
                </div>
            </div>
            <div class='clearfix' style='border:2px solid var(--orange);padding-left:60px;font-size:20px;background-color:var(--blue);color:white;font-weight:bold;'>
               <a style='display:block;color:white;font-weight:bold;".$hidden.";width:40px;text-align:center;height:58px;padding-top:18px;float:right;background-color: var(--orange)' href='".$event->getUrl()."'>></a>
               <div class='clearfix' style='padding-top:18px;'>
                ".esc_attr($event->getTitle())."
                </div>
            </div>
        </div>
        ";
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