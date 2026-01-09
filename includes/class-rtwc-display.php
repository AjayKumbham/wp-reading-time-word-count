<?php
/**
 * Display Class
 *
 * @package Reading_Time_Word_Count
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class RTWC_Display
 */
class RTWC_Display {
    
    /**
     * Get reading time HTML
     *
     * @param int $post_id Post ID
     * @return string HTML output
     */
    public function get_reading_time_html($post_id) {
        $settings = get_option('rtwc_settings');
        
        if (!$settings) {
            return '';
        }
        
        $stats = RTWC_Calculator::get_stats($post_id);
        
        $html = '<div class="rtwc-container rtwc-style-' . esc_attr($settings['display_style']) . '">';
        
        // Word count
        if ($settings['show_word_count']) {
            $html .= '<div class="rtwc-item rtwc-word-count">';
            if (!empty($settings['icon_word_count'])) {
                $html .= '<span class="rtwc-icon">' . esc_html($settings['icon_word_count']) . '</span>';
            }
            $html .= '<span class="rtwc-value">' . esc_html($stats['word_count_formatted']) . '</span>';
            $html .= '<span class="rtwc-label"> ' . esc_html($settings['label_word_count']) . '</span>';
            $html .= '</div>';
        }
        
        // Reading time
        if ($settings['show_reading_time']) {
            $html .= '<div class="rtwc-item rtwc-reading-time">';
            if (!empty($settings['icon_reading_time'])) {
                $html .= '<span class="rtwc-icon">' . esc_html($settings['icon_reading_time']) . '</span>';
            }
            $html .= '<span class="rtwc-value">' . esc_html($stats['reading_time']) . '</span>';
            $html .= '<span class="rtwc-label"> ' . esc_html($settings['label_reading_time']) . '</span>';
            $html .= '</div>';
        }
        
        $html .= '</div>';
        
        return apply_filters('rtwc_display_html', $html, $post_id, $stats, $settings);
    }
    
    /**
     * Get preview HTML for admin
     *
     * @param array $settings Settings array
     * @return string HTML output
     */
    public function get_preview_html($settings) {
        $html = '<div class="rtwc-container rtwc-style-' . esc_attr($settings['display_style']) . '">';
        
        // Word count
        if (isset($settings['show_word_count']) && $settings['show_word_count']) {
            $html .= '<div class="rtwc-item rtwc-word-count">';
            if (!empty($settings['icon_word_count'])) {
                $html .= '<span class="rtwc-icon">' . esc_html($settings['icon_word_count']) . '</span>';
            }
            $html .= '<span class="rtwc-value">1,250</span>';
            $html .= '<span class="rtwc-label"> ' . esc_html($settings['label_word_count']) . '</span>';
            $html .= '</div>';
        }
        
        // Reading time
        if (isset($settings['show_reading_time']) && $settings['show_reading_time']) {
            $html .= '<div class="rtwc-item rtwc-reading-time">';
            if (!empty($settings['icon_reading_time'])) {
                $html .= '<span class="rtwc-icon">' . esc_html($settings['icon_reading_time']) . '</span>';
            }
            $html .= '<span class="rtwc-value">6</span>';
            $html .= '<span class="rtwc-label"> ' . esc_html($settings['label_reading_time']) . '</span>';
            $html .= '</div>';
        }
        
        $html .= '</div>';
        
        return $html;
    }
}
