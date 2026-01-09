<?php
/**
 * Widget Class
 *
 * @package Reading_Time_Word_Count
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class RTWC_Widget
 */
class RTWC_Widget extends WP_Widget {
    
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct(
            'rtwc_widget',
            __('Reading Time & Word Count', 'reading-time-word-count'),
            array(
                'description' => __('Display reading time and word count for the current post', 'reading-time-word-count'),
            )
        );
    }
    
    /**
     * Widget output
     *
     * @param array $args Widget arguments
     * @param array $instance Widget instance
     */
    public function widget($args, $instance) {
        if (!is_singular()) {
            return;
        }
        
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $title = apply_filters('widget_title', $title, $instance, $this->id_base);
        
        echo $args['before_widget'];
        
        if ($title) {
            echo $args['before_title'] . esc_html($title) . $args['after_title'];
        }
        
        $display = new RTWC_Display();
        echo $display->get_reading_time_html(get_the_ID());
        
        echo $args['after_widget'];
    }
    
    /**
     * Widget form
     *
     * @param array $instance Widget instance
     */
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Post Statistics', 'reading-time-word-count');
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>">
                <?php esc_html_e('Title:', 'reading-time-word-count'); ?>
            </label>
            <input 
                class="widefat" 
                id="<?php echo esc_attr($this->get_field_id('title')); ?>" 
                name="<?php echo esc_attr($this->get_field_name('title')); ?>" 
                type="text" 
                value="<?php echo esc_attr($title); ?>"
            >
        </p>
        <?php
    }
    
    /**
     * Update widget
     *
     * @param array $new_instance New instance
     * @param array $old_instance Old instance
     * @return array Updated instance
     */
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = !empty($new_instance['title']) ? sanitize_text_field($new_instance['title']) : '';
        return $instance;
    }
}
