# Testing Documentation

## Reading Time & Word Count WordPress Plugin

**Version:** 1.0.0  
**Author:** Ajay Kumbham
**Last Updated:** January 2026

---

## Table of Contents

1. [Testing Environment Setup](#testing-environment-setup)
2. [Installation Procedures](#installation-procedures)
3. [Functional Testing](#functional-testing)
4. [Security Testing](#security-testing)
5. [Performance Testing](#performance-testing)
6. [Compatibility Testing](#compatibility-testing)
7. [User Acceptance Testing](#user-acceptance-testing)
8. [Regression Testing](#regression-testing)
9. [Test Results Documentation](#test-results-documentation)

---

## Testing Environment Setup

### Minimum Requirements

- **WordPress Version:** 5.0 or higher
- **PHP Version:** 7.2 or higher
- **MySQL Version:** 5.6 or higher
- **Web Server:** Apache 2.4+ or Nginx 1.18+
- **Browser Support:** Chrome 90+, Firefox 88+, Safari 14+, Edge 90+

### Recommended Local Development Environments

#### Option 1: Local by Flywheel
- **Platform:** Windows, macOS, Linux
- **Download:** https://localwp.com/
- **Advantages:** One-click WordPress installation, SSL support, easy database management

#### Option 2: XAMPP
- **Platform:** Windows, macOS, Linux
- **Download:** https://www.apachefriends.org/
- **Advantages:** Full LAMP/WAMP stack, phpMyAdmin included

#### Option 3: Docker
- **Platform:** Cross-platform
- **Requirements:** Docker Desktop
- **Advantages:** Isolated environments, version control

#### Option 4: WAMP Server
- **Platform:** Windows
- **Download:** https://www.wampserver.com/
- **Advantages:** Windows-optimized, Apache/MySQL/PHP stack

---

## Installation Procedures

### Method 1: Manual Installation

1. Navigate to WordPress plugins directory:
   ```
   /wp-content/plugins/
   ```

2. Copy the plugin folder:
   ```
   reading-time-word-count/
   ```

3. Set appropriate file permissions:
   - Directories: 755
   - Files: 644

4. Access WordPress Admin Dashboard

5. Navigate to **Plugins → Installed Plugins**

6. Locate "Reading Time & Word Count"

7. Click **Activate**

### Method 2: ZIP Upload

1. Create plugin archive (if not already available)

2. Access WordPress Admin Dashboard

3. Navigate to **Plugins → Add New**

4. Click **Upload Plugin**

5. Select `reading-time-word-count.zip`

6. Click **Install Now**

7. Click **Activate Plugin**

### Method 3: WP-CLI Installation

```bash
wp plugin install /path/to/reading-time-word-count.zip --activate
```

---

## Functional Testing

### 1. Plugin Activation Testing

**Objective:** Verify plugin activates without errors and initializes default settings.

| Test Case | Expected Result | Status |
|-----------|----------------|--------|
| Plugin activation | No PHP errors or warnings | ☐ |
| Default settings creation | Settings saved in `wp_options` table | ☐ |
| Activation notice display | Success notice appears in admin | ☐ |
| Database option creation | `rtwc_settings` option exists | ☐ |

### 2. Admin Settings Interface Testing

**Objective:** Validate all admin interface elements function correctly.

#### General Settings

| Test Case | Expected Result | Status |
|-----------|----------------|--------|
| Enable/Disable toggle | Plugin functionality toggles correctly | ☐ |
| Post type selection | Multiple post types can be selected | ☐ |
| Display position selector | Before/After/Manual options work | ☐ |
| Words per minute input | Accepts values 100-500 only | ☐ |
| Invalid WPM rejection | Values outside range are rejected | ☐ |

#### Display Settings

| Test Case | Expected Result | Status |
|-----------|----------------|--------|
| Show word count toggle | Checkbox toggles visibility | ☐ |
| Show reading time toggle | Checkbox toggles visibility | ☐ |
| Display style selector | All 4 styles selectable | ☐ |
| Icon customization | Custom icons/emojis accepted | ☐ |
| Label customization | Custom labels saved correctly | ☐ |
| Live preview update | Preview reflects changes in real-time | ☐ |

#### Advanced Settings

| Test Case | Expected Result | Status |
|-----------|----------------|--------|
| Exclude posts input | Comma-separated IDs processed | ☐ |
| Custom CSS input | CSS code accepted and applied | ☐ |
| Settings persistence | Settings saved after page reload | ☐ |

### 3. Frontend Display Testing

**Objective:** Verify correct rendering on public-facing pages.

#### Display Positions

| Test Case | Expected Result | Status |
|-----------|----------------|--------|
| Before content position | Display appears before post content | ☐ |
| After content position | Display appears after post content | ☐ |
| Manual position (shortcode) | Display appears at shortcode location | ☐ |

#### Display Styles

| Test Case | Expected Result | Status |
|-----------|----------------|--------|
| Modern style | Gradient background, proper styling | ☐ |
| Minimal style | Border-left accent, clean design | ☐ |
| Badge style | Rounded pill design | ☐ |
| Card style | Vertical card layout | ☐ |

### 4. Calculation Accuracy Testing

**Objective:** Validate word count and reading time calculations.

| Content Length | Expected Word Count | Expected Reading Time (200 WPM) | Actual Result | Status |
|----------------|---------------------|----------------------------------|---------------|--------|
| 100 words | 100 | 1 minute | | ☐ |
| 500 words | 500 | 3 minutes | | ☐ |
| 1000 words | 1000 | 5 minutes | | ☐ |
| 2000 words | 2000 | 10 minutes | | ☐ |
| 5000 words | 5000 | 25 minutes | | ☐ |

### 5. Shortcode Testing

**Objective:** Verify shortcode functionality.

| Test Case | Expected Result | Status |
|-----------|----------------|--------|
| `[reading_time]` in content | Displays reading stats | ☐ |
| `[reading_time post_id="123"]` | Displays stats for specific post | ☐ |
| Multiple shortcodes | Each instance renders correctly | ☐ |
| Shortcode in widgets | Functions in widget areas | ☐ |

### 6. Widget Testing

**Objective:** Validate widget functionality.

| Test Case | Expected Result | Status |
|-----------|----------------|--------|
| Widget registration | Appears in Widgets admin | ☐ |
| Widget title customization | Custom title displays | ☐ |
| Widget on single posts | Displays correctly | ☐ |
| Widget on non-singular pages | Does not display | ☐ |

---

## Security Testing

### 1. Input Sanitization

**Objective:** Verify all user inputs are properly sanitized.

| Input Field | Sanitization Function | Test Input | Status |
|-------------|----------------------|------------|--------|
| Position | `sanitize_text_field()` | `<script>alert('XSS')</script>` | ☐ |
| Display style | `sanitize_text_field()` | `'; DROP TABLE wp_posts; --` | ☐ |
| Labels | `sanitize_text_field()` | `<img src=x onerror=alert(1)>` | ☐ |
| Icons | `sanitize_text_field()` | `<svg onload=alert(1)>` | ☐ |
| Words per minute | `absint()` | `-100`, `abc`, `9999` | ☐ |
| Custom CSS | `wp_strip_all_tags()` | `<script>malicious()</script>` | ☐ |

### 2. Output Escaping

**Objective:** Verify all outputs are properly escaped.

| Output Context | Escaping Function | Status |
|----------------|-------------------|--------|
| HTML content | `esc_html()` | ☐ |
| HTML attributes | `esc_attr()` | ☐ |
| Textarea content | `esc_textarea()` | ☐ |
| URLs | `esc_url()` | ☐ |

### 3. Nonce Verification

**Objective:** Validate CSRF protection.

| Test Case | Expected Result | Status |
|-----------|----------------|--------|
| AJAX request without nonce | Request rejected | ☐ |
| AJAX request with invalid nonce | Request rejected | ☐ |
| AJAX request with valid nonce | Request processed | ☐ |

### 4. Capability Checks

**Objective:** Verify proper permission enforcement.

| Test Case | Expected Result | Status |
|-----------|----------------|--------|
| Non-admin accessing settings | Access denied | ☐ |
| Non-admin AJAX request | Request rejected | ☐ |
| Admin accessing settings | Access granted | ☐ |

### 5. Direct File Access Prevention

**Objective:** Verify files cannot be accessed directly.

| File Path | Expected Result | Status |
|-----------|----------------|--------|
| `/includes/class-rtwc-calculator.php` | Blank page or 403 | ☐ |
| `/admin/settings-page.php` | Blank page or 403 | ☐ |
| `/assets/index.php` | Blank page | ☐ |

---

## Performance Testing

### 1. Page Load Impact

**Objective:** Measure plugin impact on page load times.

| Metric | Without Plugin | With Plugin | Difference | Status |
|--------|----------------|-------------|------------|--------|
| Time to First Byte (TTFB) | | | | ☐ |
| DOM Content Loaded | | | | ☐ |
| Full Page Load | | | | ☐ |
| Total Page Size | | | | ☐ |

**Tools:** Google PageSpeed Insights, GTmetrix, WebPageTest

### 2. Database Query Analysis

**Objective:** Verify efficient database usage.

| Test Case | Expected Result | Status |
|-----------|----------------|--------|
| Number of queries added | ≤ 2 additional queries | ☐ |
| Query execution time | < 50ms per query | ☐ |
| No N+1 query problems | Verified with Query Monitor | ☐ |

### 3. Asset Loading Optimization

**Objective:** Verify assets load only when needed.

| Test Case | Expected Result | Status |
|-----------|----------------|--------|
| Frontend CSS on posts | Loads only when enabled | ☐ |
| Frontend CSS on pages | Does not load if pages excluded | ☐ |
| Admin CSS | Loads only on settings page | ☐ |
| Admin JS | Loads only on settings page | ☐ |

### 4. Caching Compatibility

**Objective:** Verify compatibility with caching plugins.

| Caching Plugin | Compatibility | Status |
|----------------|---------------|--------|
| WP Super Cache | Compatible | ☐ |
| W3 Total Cache | Compatible | ☐ |
| WP Rocket | Compatible | ☐ |
| LiteSpeed Cache | Compatible | ☐ |

---

## Compatibility Testing

### 1. WordPress Version Compatibility

| WordPress Version | Compatibility | Status |
|-------------------|---------------|--------|
| 5.0 | Compatible | ☐ |
| 5.5 | Compatible | ☐ |
| 6.0 | Compatible | ☐ |
| 6.4 (Latest) | Compatible | ☐ |

### 2. PHP Version Compatibility

| PHP Version | Compatibility | Status |
|-------------|---------------|--------|
| 7.2 | Compatible | ☐ |
| 7.4 | Compatible | ☐ |
| 8.0 | Compatible | ☐ |
| 8.1 | Compatible | ☐ |
| 8.2 | Compatible | ☐ |

### 3. Theme Compatibility

| Theme | Compatibility | Status |
|-------|---------------|--------|
| Twenty Twenty-Four | Compatible | ☐ |
| Twenty Twenty-Three | Compatible | ☐ |
| Astra | Compatible | ☐ |
| GeneratePress | Compatible | ☐ |
| OceanWP | Compatible | ☐ |

### 4. Plugin Compatibility

| Plugin | Compatibility | Status |
|--------|---------------|--------|
| Yoast SEO | Compatible | ☐ |
| WooCommerce | Compatible | ☐ |
| Contact Form 7 | Compatible | ☐ |
| Elementor | Compatible | ☐ |
| Gutenberg | Compatible | ☐ |

### 5. Browser Compatibility

| Browser | Version | Compatibility | Status |
|---------|---------|---------------|--------|
| Chrome | 90+ | Compatible | ☐ |
| Firefox | 88+ | Compatible | ☐ |
| Safari | 14+ | Compatible | ☐ |
| Edge | 90+ | Compatible | ☐ |

---

## User Acceptance Testing

### 1. Responsive Design Testing

**Objective:** Verify proper display across device sizes.

| Device Type | Resolution | Display Quality | Status |
|-------------|-----------|-----------------|--------|
| Desktop | 1920×1080 | Optimal | ☐ |
| Laptop | 1366×768 | Optimal | ☐ |
| Tablet (Landscape) | 1024×768 | Optimal | ☐ |
| Tablet (Portrait) | 768×1024 | Optimal | ☐ |
| Mobile (Large) | 414×896 | Optimal | ☐ |
| Mobile (Medium) | 375×667 | Optimal | ☐ |
| Mobile (Small) | 320×568 | Optimal | ☐ |

### 2. Accessibility Testing

**Objective:** Verify WCAG 2.1 Level AA compliance.

| Test Case | Expected Result | Status |
|-----------|----------------|--------|
| Keyboard navigation | All elements accessible via keyboard | ☐ |
| Screen reader compatibility | Proper ARIA labels and semantic HTML | ☐ |
| Color contrast | Meets WCAG AA standards (4.5:1) | ☐ |
| Focus indicators | Visible focus states on interactive elements | ☐ |

**Tools:** WAVE, axe DevTools, Lighthouse Accessibility Audit

### 3. Internationalization Testing

**Objective:** Verify translation readiness.

| Test Case | Expected Result | Status |
|-----------|----------------|--------|
| All strings wrapped in translation functions | `__()`, `_e()`, `_n()` used correctly | ☐ |
| Text domain consistency | `reading-time-word-count` used throughout | ☐ |
| RTL language support | Layout adapts for RTL languages | ☐ |

---

## Regression Testing

### 1. Plugin Update Testing

**Objective:** Verify smooth updates without data loss.

| Test Case | Expected Result | Status |
|-----------|----------------|--------|
| Settings persistence after update | All settings retained | ☐ |
| Database schema integrity | No data corruption | ☐ |
| Backward compatibility | Previous version data migrates correctly | ☐ |

### 2. Deactivation Testing

**Objective:** Verify clean deactivation.

| Test Case | Expected Result | Status |
|-----------|----------------|--------|
| Frontend display removal | No output on frontend after deactivation | ☐ |
| Settings persistence | Settings retained for reactivation | ☐ |
| No PHP errors | Clean deactivation without warnings | ☐ |

### 3. Uninstallation Testing

**Objective:** Verify complete cleanup on uninstall.

| Test Case | Expected Result | Status |
|-----------|----------------|--------|
| Options removal | `rtwc_settings` deleted from database | ☐ |
| Transients cleanup | All plugin transients removed | ☐ |
| Multisite cleanup | Data removed from all sites | ☐ |
| Cache flush | WordPress cache cleared | ☐ |

---

## Test Results Documentation

### Test Execution Summary

**Test Date:** _______________  
**Tester Name:** _______________  
**Environment:** _______________  
**WordPress Version:** _______________  
**PHP Version:** _______________

### Results Overview

| Test Category | Total Tests | Passed | Failed | Skipped |
|---------------|-------------|--------|--------|---------|
| Functional Testing | | | | |
| Security Testing | | | | |
| Performance Testing | | | | |
| Compatibility Testing | | | | |
| User Acceptance Testing | | | | |
| Regression Testing | | | | |
| **TOTAL** | | | | |

### Critical Issues

| Issue ID | Severity | Description | Status |
|----------|----------|-------------|--------|
| | | | |

### Known Limitations

1. 
2. 
3. 

### Recommendations

1. 
2. 
3. 

---

## Automated Testing (Optional)

### PHPUnit Tests

```bash
# Install PHPUnit
composer require --dev phpunit/phpunit

# Run tests
vendor/bin/phpunit tests/
```

### WordPress Plugin Check

```bash
# Install Plugin Check
wp plugin install plugin-check --activate

# Run check
wp plugin check reading-time-word-count
```

### Code Quality Tools

```bash
# PHP_CodeSniffer (WordPress Coding Standards)
composer require --dev squizlabs/php_codesniffer
composer require --dev wp-coding-standards/wpcs

# Run PHPCS
vendor/bin/phpcs --standard=WordPress .
```

---

## References

- [WordPress Plugin Handbook](https://developer.wordpress.org/plugins/)
- [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/)
- [WordPress Security Best Practices](https://developer.wordpress.org/plugins/security/)
- [WCAG 2.1 Guidelines](https://www.w3.org/WAI/WCAG21/quickref/)

---


