<?php
/**
 * Uninstall Script
 * 
 * This file runs when the plugin is uninstalled (deleted).
 * It cleans up all plugin data from the database.
 *
 * @package Reading_Time_Word_Count
 */

// Exit if accessed directly or not uninstalling
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Delete plugin options
delete_option('rtwc_settings');

// Delete transients
delete_transient('rtwc_activation_notice');

// For multisite installations
if (is_multisite()) {
    global $wpdb;
    
    $blog_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
    
    foreach ($blog_ids as $blog_id) {
        switch_to_blog($blog_id);
        
        delete_option('rtwc_settings');
        delete_transient('rtwc_activation_notice');
        
        restore_current_blog();
    }
}

// Clear any cached data
wp_cache_flush();
