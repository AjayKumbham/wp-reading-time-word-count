<?php
/**
 * Plugin Name: Reading Time & Word Count
 * Plugin URI: https://github.com/ajaykumbham/wp-reading-time-word-count
 * Description: A professional WordPress plugin that displays reading time and word count for posts with customizable styling and placement options.
 * Version: 1.0.0
 * Author: Ajay Kumbham
 * Author URI: https://ajaykumbham.vercel.app
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: reading-time-word-count
 * Domain Path: /languages
 * Requires at least: 5.0
 * Requires PHP: 7.2
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('RTWC_VERSION', '1.0.0');
define('RTWC_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('RTWC_PLUGIN_URL', plugin_dir_url(__FILE__));
define('RTWC_PLUGIN_BASENAME', plugin_basename(__FILE__));

/**
 * Main Plugin Class
 */
class Reading_Time_Word_Count {
    
    /**
     * Single instance of the class
     */
    private static $instance = null;
    
    /**
     * Get single instance
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructor
     */
    private function __construct() {
        $this->init_hooks();
        $this->load_dependencies();
    }
    
    /**
     * Initialize hooks
     */
    private function init_hooks() {
        // Activation and deactivation hooks
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
        
        // Admin hooks
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
        
        // Frontend hooks
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_assets'));
        add_filter('the_content', array($this, 'add_reading_time_to_content'), 10);
        
        // Shortcode
        add_shortcode('reading_time', array($this, 'reading_time_shortcode'));
        
        // Widget
        add_action('widgets_init', array($this, 'register_widget'));
        
        // AJAX hooks for live preview
        add_action('wp_ajax_rtwc_preview', array($this, 'ajax_preview'));
    }
    
    /**
     * Load plugin dependencies
     */
    private function load_dependencies() {
        require_once RTWC_PLUGIN_DIR . 'includes/class-rtwc-calculator.php';
        require_once RTWC_PLUGIN_DIR . 'includes/class-rtwc-widget.php';
        require_once RTWC_PLUGIN_DIR . 'includes/class-rtwc-display.php';
    }
    
    /**
     * Plugin activation
     */
    public function activate() {
        // Set default options
        $default_options = array(
            'enabled' => true,
            'post_types' => array('post'),
            'position' => 'before',
            'show_word_count' => true,
            'show_reading_time' => true,
            'words_per_minute' => 200,
            'label_word_count' => 'Words',
            'label_reading_time' => 'min read',
            'icon_word_count' => '📝',
            'icon_reading_time' => '⏱️',
            'display_style' => 'modern',
            'custom_css' => '',
            'exclude_posts' => array(),
        );
        
        add_option('rtwc_settings', $default_options);
        
        // Create a transient for activation notice
        set_transient('rtwc_activation_notice', true, 5);
    }
    
    /**
     * Plugin deactivation
     */
    public function deactivate() {
        // Clean up transients
        delete_transient('rtwc_activation_notice');
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_options_page(
            __('Reading Time & Word Count Settings', 'reading-time-word-count'),
            __('Reading Time', 'reading-time-word-count'),
            'manage_options',
            'reading-time-word-count',
            array($this, 'render_settings_page')
        );
    }
    
    /**
     * Register settings
     */
    public function register_settings() {
        register_setting(
            'rtwc_settings_group',
            'rtwc_settings',
            array($this, 'sanitize_settings')
        );
    }
    
    /**
     * Sanitize settings
     */
    public function sanitize_settings($input) {
        $sanitized = array();
        
        // Boolean values
        $sanitized['enabled'] = isset($input['enabled']) ? true : false;
        $sanitized['show_word_count'] = isset($input['show_word_count']) ? true : false;
        $sanitized['show_reading_time'] = isset($input['show_reading_time']) ? true : false;
        
        // Text values
        $sanitized['position'] = sanitize_text_field($input['position']);
        $sanitized['display_style'] = sanitize_text_field($input['display_style']);
        $sanitized['label_word_count'] = sanitize_text_field($input['label_word_count']);
        $sanitized['label_reading_time'] = sanitize_text_field($input['label_reading_time']);
        $sanitized['icon_word_count'] = sanitize_text_field($input['icon_word_count']);
        $sanitized['icon_reading_time'] = sanitize_text_field($input['icon_reading_time']);
        
        // Numeric values
        $sanitized['words_per_minute'] = absint($input['words_per_minute']);
        if ($sanitized['words_per_minute'] < 100 || $sanitized['words_per_minute'] > 500) {
            $sanitized['words_per_minute'] = 200;
        }
        
        // Arrays
        $sanitized['post_types'] = isset($input['post_types']) ? array_map('sanitize_text_field', $input['post_types']) : array('post');
        $sanitized['exclude_posts'] = isset($input['exclude_posts']) ? array_map('absint', explode(',', $input['exclude_posts'])) : array();
        
        // CSS
        $sanitized['custom_css'] = wp_strip_all_tags($input['custom_css']);
        
        return $sanitized;
    }
    
    /**
     * Enqueue admin assets
     */
    public function enqueue_admin_assets($hook) {
        if ('settings_page_reading-time-word-count' !== $hook) {
            return;
        }
        
        wp_enqueue_style(
            'rtwc-admin-style',
            RTWC_PLUGIN_URL . 'assets/css/admin.css',
            array(),
            RTWC_VERSION
        );
        
        wp_enqueue_script(
            'rtwc-admin-script',
            RTWC_PLUGIN_URL . 'assets/js/admin.js',
            array('jquery'),
            RTWC_VERSION,
            true
        );
        
        wp_localize_script('rtwc-admin-script', 'rtwcAdmin', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('rtwc_preview_nonce'),
        ));
    }
    
    /**
     * Enqueue frontend assets
     */
    public function enqueue_frontend_assets() {
        $settings = get_option('rtwc_settings');
        
        if (!$settings || !$settings['enabled']) {
            return;
        }
        
        wp_enqueue_style(
            'rtwc-frontend-style',
            RTWC_PLUGIN_URL . 'assets/css/frontend.css',
            array(),
            RTWC_VERSION
        );
        
        // Add custom CSS if provided
        if (!empty($settings['custom_css'])) {
            wp_add_inline_style('rtwc-frontend-style', $settings['custom_css']);
        }
    }
    
    /**
     * Add reading time to content
     */
    public function add_reading_time_to_content($content) {
        $settings = get_option('rtwc_settings');
        
        // Check if plugin is enabled
        if (!$settings || !$settings['enabled']) {
            return $content;
        }
        
        // Check if current post type is enabled
        if (!in_array(get_post_type(), $settings['post_types'])) {
            return $content;
        }
        
        // Check if current post is excluded
        if (in_array(get_the_ID(), $settings['exclude_posts'])) {
            return $content;
        }
        
        // Check if we're in the main loop
        if (!is_singular() || !in_the_loop() || !is_main_query()) {
            return $content;
        }
        
        $display = new RTWC_Display();
        $reading_time_html = $display->get_reading_time_html(get_the_ID());
        
        // Add based on position
        if ($settings['position'] === 'before') {
            return $reading_time_html . $content;
        } elseif ($settings['position'] === 'after') {
            return $content . $reading_time_html;
        }
        
        return $content;
    }
    
    /**
     * Reading time shortcode
     */
    public function reading_time_shortcode($atts) {
        $atts = shortcode_atts(array(
            'post_id' => get_the_ID(),
        ), $atts);
        
        $display = new RTWC_Display();
        return $display->get_reading_time_html($atts['post_id']);
    }
    
    /**
     * Register widget
     */
    public function register_widget() {
        register_widget('RTWC_Widget');
    }
    
    /**
     * AJAX preview handler
     */
    public function ajax_preview() {
        check_ajax_referer('rtwc_preview_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized');
        }
        
        $settings = isset($_POST['settings']) ? $_POST['settings'] : array();
        
        $display = new RTWC_Display();
        $html = $display->get_preview_html($settings);
        
        wp_send_json_success($html);
    }
    
    /**
     * Render settings page
     */
    public function render_settings_page() {
        require_once RTWC_PLUGIN_DIR . 'admin/settings-page.php';
    }
}

// Initialize the plugin
function rtwc_init() {
    return Reading_Time_Word_Count::get_instance();
}

// Start the plugin
rtwc_init();
