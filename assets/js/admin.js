/**
 * Admin JavaScript
 * Reading Time & Word Count Plugin
 */

(function($) {
    'use strict';
    
    $(document).ready(function() {
        
        // Live Preview Update
        function updatePreview() {
            const settings = {
                show_word_count: $('#rtwc_show_word_count').is(':checked'),
                show_reading_time: $('#rtwc_show_reading_time').is(':checked'),
                display_style: $('#rtwc_display_style').val(),
                icon_word_count: $('#rtwc_icon_word_count').val(),
                icon_reading_time: $('#rtwc_icon_reading_time').val(),
                label_word_count: $('#rtwc_label_word_count').val(),
                label_reading_time: $('#rtwc_label_reading_time').val(),
            };
            
            $.ajax({
                url: rtwcAdmin.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'rtwc_preview',
                    nonce: rtwcAdmin.nonce,
                    settings: settings
                },
                beforeSend: function() {
                    $('#rtwc-preview-container').html('<div class="rtwc-loading"></div>');
                },
                success: function(response) {
                    if (response.success) {
                        $('#rtwc-preview-container').html(response.data);
                        
                        // Add animation
                        $('#rtwc-preview-container .rtwc-container').hide().fadeIn(300);
                    }
                },
                error: function() {
                    $('#rtwc-preview-container').html(
                        '<p style="color: #dc3545;">Preview update failed. Please try again.</p>'
                    );
                }
            });
        }
        
        // Debounce function
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }
        
        // Create debounced preview update
        const debouncedUpdatePreview = debounce(updatePreview, 500);
        
        // Attach event listeners for live preview
        $('#rtwc_show_word_count, #rtwc_show_reading_time').on('change', updatePreview);
        $('#rtwc_display_style').on('change', updatePreview);
        $('#rtwc_icon_word_count, #rtwc_icon_reading_time').on('input', debouncedUpdatePreview);
        $('#rtwc_label_word_count, #rtwc_label_reading_time').on('input', debouncedUpdatePreview);
        
        // Form validation
        $('#rtwc-settings-form').on('submit', function(e) {
            const wordsPerMinute = parseInt($('#rtwc_words_per_minute').val());
            
            if (wordsPerMinute < 100 || wordsPerMinute > 500) {
                e.preventDefault();
                alert('Reading speed must be between 100 and 500 words per minute.');
                $('#rtwc_words_per_minute').focus();
                return false;
            }
            
            // Check if at least one display option is selected
            const showWordCount = $('#rtwc_show_word_count').is(':checked');
            const showReadingTime = $('#rtwc_show_reading_time').is(':checked');
            
            if (!showWordCount && !showReadingTime) {
                e.preventDefault();
                alert('Please select at least one display option (Word Count or Reading Time).');
                return false;
            }
            
            // Show loading state
            const submitButton = $(this).find('input[type="submit"]');
            submitButton.prop('disabled', true).val('Saving...');
        });
        
        // Shortcode copy functionality
        $('.rtwc-usage-guide code').on('click', function() {
            const text = $(this).text();
            
            // Create temporary textarea
            const $temp = $('<textarea>');
            $('body').append($temp);
            $temp.val(text).select();
            
            try {
                document.execCommand('copy');
                
                // Show feedback
                const originalText = $(this).text();
                $(this).text('Copied!').css('background', '#d4edda');
                
                setTimeout(() => {
                    $(this).text(originalText).css('background', '#f8f9fa');
                }, 2000);
            } catch (err) {
                console.error('Failed to copy text:', err);
            }
            
            $temp.remove();
        });
        
        // Add tooltip to code blocks
        $('.rtwc-usage-guide code').attr('title', 'Click to copy').css('cursor', 'pointer');
        
        // Smooth scroll to error fields
        if ($('.error').length) {
            $('html, body').animate({
                scrollTop: $('.error').first().offset().top - 100
            }, 500);
        }
        
        // Character counter for custom CSS
        $('#rtwc_custom_css').on('input', function() {
            const length = $(this).val().length;
            let counter = $(this).next('.char-counter');
            
            if (!counter.length) {
                counter = $('<div class="char-counter" style="font-size: 12px; color: #666; margin-top: 4px;"></div>');
                $(this).after(counter);
            }
            
            counter.text(length + ' characters');
        });
        
        // Toggle advanced settings
        const advancedCard = $('.rtwc-card').last();
        const advancedToggle = $('<button type="button" class="button" style="margin-bottom: 10px;">Toggle Advanced Settings</button>');
        
        advancedCard.before(advancedToggle);
        advancedCard.hide();
        
        advancedToggle.on('click', function() {
            advancedCard.slideToggle(300);
            $(this).text(advancedCard.is(':visible') ? 'Hide Advanced Settings' : 'Show Advanced Settings');
        });
        
        // Auto-save draft (optional feature)
        let autoSaveTimeout;
        
        $('#rtwc-settings-form input, #rtwc-settings-form select, #rtwc-settings-form textarea').on('change input', function() {
            clearTimeout(autoSaveTimeout);
            
            // Show unsaved changes indicator
            if (!$('.unsaved-indicator').length) {
                $('#rtwc-settings-form').prepend(
                    '<div class="unsaved-indicator" style="background: #fff3cd; padding: 10px; margin-bottom: 15px; border-left: 4px solid #ffc107; border-radius: 4px;">' +
                    '<strong>Unsaved changes</strong> - Don\'t forget to save your settings!' +
                    '</div>'
                );
            }
        });
        
        // Remove unsaved indicator on save
        $('#rtwc-settings-form').on('submit', function() {
            $('.unsaved-indicator').remove();
        });
        
        // Keyboard shortcuts
        $(document).on('keydown', function(e) {
            // Ctrl/Cmd + S to save
            if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                e.preventDefault();
                $('#rtwc-settings-form').submit();
            }
        });
        
        // Initialize tooltips (if WordPress admin has them)
        if (typeof $.fn.tooltip === 'function') {
            $('[title]').tooltip();
        }
        
        console.log('Reading Time & Word Count Admin JS loaded successfully');
    });
    
})(jQuery);
