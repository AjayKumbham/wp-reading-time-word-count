<?php
/**
 * Reading Time Calculator Class
 *
 * @package Reading_Time_Word_Count
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class RTWC_Calculator
 */
class RTWC_Calculator {
    
    /**
     * Calculate word count for a post
     *
     * @param int $post_id Post ID
     * @return int Word count
     */
    public static function get_word_count($post_id) {
        $post = get_post($post_id);
        
        if (!$post) {
            return 0;
        }
        
        // Get post content
        $content = $post->post_content;
        
        // Strip shortcodes
        $content = strip_shortcodes($content);
        
        // Strip HTML tags
        $content = wp_strip_all_tags($content);
        
        // Remove extra whitespace
        $content = trim(preg_replace('/\s+/', ' ', $content));
        
        // Count words
        $word_count = str_word_count($content);
        
        // Allow filtering
        return apply_filters('rtwc_word_count', $word_count, $post_id);
    }
    
    /**
     * Calculate reading time for a post
     *
     * @param int $post_id Post ID
     * @param int $words_per_minute Words per minute (default: 200)
     * @return int Reading time in minutes
     */
    public static function get_reading_time($post_id, $words_per_minute = 200) {
        $word_count = self::get_word_count($post_id);
        
        if ($word_count === 0) {
            return 0;
        }
        
        // Calculate reading time
        $reading_time = ceil($word_count / $words_per_minute);
        
        // Ensure minimum of 1 minute
        $reading_time = max(1, $reading_time);
        
        // Allow filtering
        return apply_filters('rtwc_reading_time', $reading_time, $post_id, $words_per_minute);
    }
    
    /**
     * Get formatted reading time string
     *
     * @param int $post_id Post ID
     * @param int $words_per_minute Words per minute
     * @return string Formatted reading time
     */
    public static function get_reading_time_formatted($post_id, $words_per_minute = 200) {
        $reading_time = self::get_reading_time($post_id, $words_per_minute);
        
        if ($reading_time < 1) {
            return __('Less than a minute', 'reading-time-word-count');
        }
        
        if ($reading_time === 1) {
            return __('1 minute', 'reading-time-word-count');
        }
        
        return sprintf(
            _n('%d minute', '%d minutes', $reading_time, 'reading-time-word-count'),
            $reading_time
        );
    }
    
    /**
     * Get formatted word count string
     *
     * @param int $post_id Post ID
     * @return string Formatted word count
     */
    public static function get_word_count_formatted($post_id) {
        $word_count = self::get_word_count($post_id);
        
        return number_format_i18n($word_count);
    }
    
    /**
     * Get all stats for a post
     *
     * @param int $post_id Post ID
     * @return array Array with word_count and reading_time
     */
    public static function get_stats($post_id) {
        $settings = get_option('rtwc_settings');
        $words_per_minute = isset($settings['words_per_minute']) ? $settings['words_per_minute'] : 200;
        
        return array(
            'word_count' => self::get_word_count($post_id),
            'word_count_formatted' => self::get_word_count_formatted($post_id),
            'reading_time' => self::get_reading_time($post_id, $words_per_minute),
            'reading_time_formatted' => self::get_reading_time_formatted($post_id, $words_per_minute),
        );
    }
}
