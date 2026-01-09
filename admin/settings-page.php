<?php
/**
 * Admin Settings Page
 *
 * @package Reading_Time_Word_Count
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

$settings = get_option('rtwc_settings');
$post_types = get_post_types(array('public' => true), 'objects');
?>

<div class="wrap rtwc-admin-wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    
    <?php
    // Show activation notice
    if (get_transient('rtwc_activation_notice')) {
        ?>
        <div class="notice notice-success is-dismissible">
            <p><?php esc_html_e('Reading Time & Word Count plugin has been activated successfully!', 'reading-time-word-count'); ?></p>
        </div>
        <?php
        delete_transient('rtwc_activation_notice');
    }
    
    // Show settings saved notice
    if (isset($_GET['settings-updated'])) {
        ?>
        <div class="notice notice-success is-dismissible">
            <p><?php esc_html_e('Settings saved successfully!', 'reading-time-word-count'); ?></p>
        </div>
        <?php
    }
    ?>
    
    <div class="rtwc-admin-container">
        <div class="rtwc-admin-main">
            <form method="post" action="options.php" id="rtwc-settings-form">
                <?php
                settings_fields('rtwc_settings_group');
                ?>
                
                <!-- General Settings -->
                <div class="rtwc-card">
                    <h2><?php esc_html_e('General Settings', 'reading-time-word-count'); ?></h2>
                    
                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <label for="rtwc_enabled">
                                    <?php esc_html_e('Enable Plugin', 'reading-time-word-count'); ?>
                                </label>
                            </th>
                            <td>
                                <label class="rtwc-toggle">
                                    <input 
                                        type="checkbox" 
                                        name="rtwc_settings[enabled]" 
                                        id="rtwc_enabled" 
                                        value="1" 
                                        <?php checked($settings['enabled'], true); ?>
                                    >
                                    <span class="rtwc-toggle-slider"></span>
                                </label>
                                <p class="description">
                                    <?php esc_html_e('Enable or disable the reading time and word count display.', 'reading-time-word-count'); ?>
                                </p>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">
                                <?php esc_html_e('Post Types', 'reading-time-word-count'); ?>
                            </th>
                            <td>
                                <?php foreach ($post_types as $post_type) : ?>
                                    <label style="display: block; margin-bottom: 8px;">
                                        <input 
                                            type="checkbox" 
                                            name="rtwc_settings[post_types][]" 
                                            value="<?php echo esc_attr($post_type->name); ?>"
                                            <?php checked(in_array($post_type->name, $settings['post_types'])); ?>
                                        >
                                        <?php echo esc_html($post_type->labels->name); ?>
                                    </label>
                                <?php endforeach; ?>
                                <p class="description">
                                    <?php esc_html_e('Select post types where reading time should be displayed.', 'reading-time-word-count'); ?>
                                </p>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">
                                <label for="rtwc_position">
                                    <?php esc_html_e('Display Position', 'reading-time-word-count'); ?>
                                </label>
                            </th>
                            <td>
                                <select name="rtwc_settings[position]" id="rtwc_position" class="rtwc-select">
                                    <option value="before" <?php selected($settings['position'], 'before'); ?>>
                                        <?php esc_html_e('Before Content', 'reading-time-word-count'); ?>
                                    </option>
                                    <option value="after" <?php selected($settings['position'], 'after'); ?>>
                                        <?php esc_html_e('After Content', 'reading-time-word-count'); ?>
                                    </option>
                                    <option value="manual" <?php selected($settings['position'], 'manual'); ?>>
                                        <?php esc_html_e('Manual (Use Shortcode)', 'reading-time-word-count'); ?>
                                    </option>
                                </select>
                                <p class="description">
                                    <?php esc_html_e('Choose where to display the reading time. Use shortcode [reading_time] for manual placement.', 'reading-time-word-count'); ?>
                                </p>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">
                                <label for="rtwc_words_per_minute">
                                    <?php esc_html_e('Reading Speed (WPM)', 'reading-time-word-count'); ?>
                                </label>
                            </th>
                            <td>
                                <input 
                                    type="number" 
                                    name="rtwc_settings[words_per_minute]" 
                                    id="rtwc_words_per_minute" 
                                    value="<?php echo esc_attr($settings['words_per_minute']); ?>" 
                                    min="100" 
                                    max="500" 
                                    step="10"
                                    class="small-text"
                                >
                                <p class="description">
                                    <?php esc_html_e('Average reading speed in words per minute (100-500). Default: 200.', 'reading-time-word-count'); ?>
                                </p>
                            </td>
                        </tr>
                    </table>
                </div>
                
                <!-- Display Settings -->
                <div class="rtwc-card">
                    <h2><?php esc_html_e('Display Settings', 'reading-time-word-count'); ?></h2>
                    
                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <?php esc_html_e('Show Elements', 'reading-time-word-count'); ?>
                            </th>
                            <td>
                                <label style="display: block; margin-bottom: 8px;">
                                    <input 
                                        type="checkbox" 
                                        name="rtwc_settings[show_word_count]" 
                                        id="rtwc_show_word_count" 
                                        value="1" 
                                        <?php checked($settings['show_word_count'], true); ?>
                                    >
                                    <?php esc_html_e('Show Word Count', 'reading-time-word-count'); ?>
                                </label>
                                <label style="display: block;">
                                    <input 
                                        type="checkbox" 
                                        name="rtwc_settings[show_reading_time]" 
                                        id="rtwc_show_reading_time" 
                                        value="1" 
                                        <?php checked($settings['show_reading_time'], true); ?>
                                    >
                                    <?php esc_html_e('Show Reading Time', 'reading-time-word-count'); ?>
                                </label>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">
                                <label for="rtwc_display_style">
                                    <?php esc_html_e('Display Style', 'reading-time-word-count'); ?>
                                </label>
                            </th>
                            <td>
                                <select name="rtwc_settings[display_style]" id="rtwc_display_style" class="rtwc-select">
                                    <option value="modern" <?php selected($settings['display_style'], 'modern'); ?>>
                                        <?php esc_html_e('Modern', 'reading-time-word-count'); ?>
                                    </option>
                                    <option value="minimal" <?php selected($settings['display_style'], 'minimal'); ?>>
                                        <?php esc_html_e('Minimal', 'reading-time-word-count'); ?>
                                    </option>
                                    <option value="badge" <?php selected($settings['display_style'], 'badge'); ?>>
                                        <?php esc_html_e('Badge', 'reading-time-word-count'); ?>
                                    </option>
                                    <option value="card" <?php selected($settings['display_style'], 'card'); ?>>
                                        <?php esc_html_e('Card', 'reading-time-word-count'); ?>
                                    </option>
                                </select>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">
                                <label for="rtwc_icon_word_count">
                                    <?php esc_html_e('Word Count Icon', 'reading-time-word-count'); ?>
                                </label>
                            </th>
                            <td>
                                <input 
                                    type="text" 
                                    name="rtwc_settings[icon_word_count]" 
                                    id="rtwc_icon_word_count" 
                                    value="<?php echo esc_attr($settings['icon_word_count']); ?>" 
                                    class="regular-text"
                                    placeholder="📝"
                                >
                                <p class="description">
                                    <?php esc_html_e('Emoji or icon for word count (e.g., 📝, 📄, ✍️)', 'reading-time-word-count'); ?>
                                </p>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">
                                <label for="rtwc_label_word_count">
                                    <?php esc_html_e('Word Count Label', 'reading-time-word-count'); ?>
                                </label>
                            </th>
                            <td>
                                <input 
                                    type="text" 
                                    name="rtwc_settings[label_word_count]" 
                                    id="rtwc_label_word_count" 
                                    value="<?php echo esc_attr($settings['label_word_count']); ?>" 
                                    class="regular-text"
                                >
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">
                                <label for="rtwc_icon_reading_time">
                                    <?php esc_html_e('Reading Time Icon', 'reading-time-word-count'); ?>
                                </label>
                            </th>
                            <td>
                                <input 
                                    type="text" 
                                    name="rtwc_settings[icon_reading_time]" 
                                    id="rtwc_icon_reading_time" 
                                    value="<?php echo esc_attr($settings['icon_reading_time']); ?>" 
                                    class="regular-text"
                                    placeholder="⏱️"
                                >
                                <p class="description">
                                    <?php esc_html_e('Emoji or icon for reading time (e.g., ⏱️, ⏰, 🕐)', 'reading-time-word-count'); ?>
                                </p>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">
                                <label for="rtwc_label_reading_time">
                                    <?php esc_html_e('Reading Time Label', 'reading-time-word-count'); ?>
                                </label>
                            </th>
                            <td>
                                <input 
                                    type="text" 
                                    name="rtwc_settings[label_reading_time]" 
                                    id="rtwc_label_reading_time" 
                                    value="<?php echo esc_attr($settings['label_reading_time']); ?>" 
                                    class="regular-text"
                                >
                            </td>
                        </tr>
                    </table>
                </div>
                
                <!-- Advanced Settings -->
                <div class="rtwc-card">
                    <h2><?php esc_html_e('Advanced Settings', 'reading-time-word-count'); ?></h2>
                    
                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <label for="rtwc_exclude_posts">
                                    <?php esc_html_e('Exclude Posts', 'reading-time-word-count'); ?>
                                </label>
                            </th>
                            <td>
                                <input 
                                    type="text" 
                                    name="rtwc_settings[exclude_posts]" 
                                    id="rtwc_exclude_posts" 
                                    value="<?php echo esc_attr(implode(',', $settings['exclude_posts'])); ?>" 
                                    class="large-text"
                                    placeholder="123, 456, 789"
                                >
                                <p class="description">
                                    <?php esc_html_e('Comma-separated post IDs to exclude from displaying reading time.', 'reading-time-word-count'); ?>
                                </p>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">
                                <label for="rtwc_custom_css">
                                    <?php esc_html_e('Custom CSS', 'reading-time-word-count'); ?>
                                </label>
                            </th>
                            <td>
                                <textarea 
                                    name="rtwc_settings[custom_css]" 
                                    id="rtwc_custom_css" 
                                    rows="10" 
                                    class="large-text code"
                                ><?php echo esc_textarea($settings['custom_css']); ?></textarea>
                                <p class="description">
                                    <?php esc_html_e('Add custom CSS to style the reading time display.', 'reading-time-word-count'); ?>
                                </p>
                            </td>
                        </tr>
                    </table>
                </div>
                
                <?php submit_button(__('Save Settings', 'reading-time-word-count'), 'primary large'); ?>
            </form>
        </div>
        
        <!-- Sidebar -->
        <div class="rtwc-admin-sidebar">
            <!-- Live Preview -->
            <div class="rtwc-card">
                <h3><?php esc_html_e('Live Preview', 'reading-time-word-count'); ?></h3>
                <div id="rtwc-preview-container">
                    <?php
                    $display = new RTWC_Display();
                    echo $display->get_preview_html($settings);
                    ?>
                </div>
            </div>
            
            <!-- Usage Guide -->
            <div class="rtwc-card">
                <h3><?php esc_html_e('Usage Guide', 'reading-time-word-count'); ?></h3>
                <div class="rtwc-usage-guide">
                    <h4><?php esc_html_e('Shortcode', 'reading-time-word-count'); ?></h4>
                    <code>[reading_time]</code>
                    <p><?php esc_html_e('Use this shortcode to display reading time anywhere in your content.', 'reading-time-word-count'); ?></p>
                    
                    <h4><?php esc_html_e('Widget', 'reading-time-word-count'); ?></h4>
                    <p><?php esc_html_e('Go to Appearance → Widgets and add the "Reading Time & Word Count" widget to any widget area.', 'reading-time-word-count'); ?></p>
                    
                    <h4><?php esc_html_e('Template Tag', 'reading-time-word-count'); ?></h4>
                    <code>&lt;?php echo do_shortcode('[reading_time]'); ?&gt;</code>
                    <p><?php esc_html_e('Use this in your theme templates.', 'reading-time-word-count'); ?></p>
                </div>
            </div>
            
            <!-- Support -->
            <div class="rtwc-card">
                <h3><?php esc_html_e('Support', 'reading-time-word-count'); ?></h3>
                <p><?php esc_html_e('Need help? Check out the documentation or contact support.', 'reading-time-word-count'); ?></p>
                <a href="https://github.com/ajaykumbham/wp-reading-time-word-count" target="_blank" class="button button-secondary">
                    <?php esc_html_e('View Documentation', 'reading-time-word-count'); ?>
                </a>
            </div>
        </div>
    </div>
</div>
