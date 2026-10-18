<?php
/**
 * Worprees Shop Theme Functions
 * Main theme setup, enqueues, and initialization
 */

if (!defined('ABSPATH')) {
    exit;
}

// Define theme constants
if (!defined('WORPREES_SHOP_VERSION')) {
    define('WORPREES_SHOP_VERSION', '1.0.0');
}
if (!defined('WORPREES_SHOP_DIR')) {
    define('WORPREES_SHOP_DIR', get_template_directory());
}
if (!defined('WORPREES_SHOP_URI')) {
    define('WORPREES_SHOP_URI', get_template_directory_uri());
}

// Theme setup
if (!function_exists('worprees_shop_setup')) {
    function worprees_shop_setup() {
        // Text domain
        load_theme_textdomain('worprees', get_template_directory() . '/languages');
        
        // Title tag
        add_theme_support('title-tag');
        
        // Post thumbnails
        add_theme_support('post-thumbnails');
        
        // Custom logo
        add_theme_support('custom-logo', array(
            'height' => 100,
            'width' => 400,
            'flex-height' => true,
            'flex-width' => true,
        ));
        
        // HTML5 support
        add_theme_support('html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'script',
            'style',
        ));
        
        // Automatic feed links
        add_theme_support('automatic-feed-links');
        
        // Custom background
        add_theme_support('custom-background', array(
            'default-color' => 'f5f6fa',
        ));
        
        // Editor styles
        add_theme_support('editor-styles');
        
        // Responsive embeds
        add_theme_support('responsive-embeds');
        
        // Align wide
        add_theme_support('align-wide');
        
        // Register navigation menus
        register_nav_menus(array(
            'primary' => esc_html__('منوی اصلی', 'worprees'),
            'topbar' => esc_html__('منوی بالای سربرگ', 'worprees'),
            'footer' => esc_html__('منوی فوتر', 'worprees'),
            'mobile' => esc_html__('منوی موبایل', 'worprees'),
        ));
        
        // Image sizes
        add_image_size('worprees-thumbnail', 300, 200, true);
        add_image_size('worprees-medium', 600, 400, true);
        add_image_size('worprees-large', 1200, 800, true);
        add_image_size('worprees-hero', 1920, 800, true);
        add_image_size('worprees-product', 400, 400, true);
    }
    add_action('after_setup_theme', 'worprees_shop_setup');
}

// Content width
if (!function_exists('worprees_shop_content_width')) {
    function worprees_shop_content_width() {
        $GLOBALS['content_width'] = apply_filters('worprees_shop_content_width', 1140);
    }
    add_action('after_setup_theme', 'worprees_shop_content_width', 0);
}

// Register widgets
if (!function_exists('worprees_shop_widgets_init')) {
    function worprees_shop_widgets_init() {
        // Sidebar
        register_sidebar(array(
            'name' => esc_html__('نوار کناری', 'worprees'),
            'id' => 'sidebar',
            'description' => esc_html__('ابزارک‌های نوار کناری', 'worprees'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ));
        
        // Footer widgets
        for ($i = 1; $i <= 4; $i++) {
            register_sidebar(array(
                'name' => sprintf(esc_html__('فوتر %d', 'worprees'), $i),
                'id' => 'footer-' . $i,
                'description' => sprintf(esc_html__('ابزارک‌های فوتر ستون %d', 'worprees'), $i),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<h3 class="widget-title">',
                'after_title' => '</h3>',
            ));
        }
        
        // Shop sidebar
        if (class_exists('WooCommerce')) {
            register_sidebar(array(
                'name' => esc_html__('نوار کناری فروشگاه', 'worprees'),
                'id' => 'shop-sidebar',
                'description' => esc_html__('ابزارک‌های نوار کناری فروشگاه', 'worprees'),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<h3 class="widget-title">',
                'after_title' => '</h3>',
            ));
        }
    }
    add_action('widgets_init', 'worprees_shop_widgets_init');
}

// Enqueue scripts and styles
if (!function_exists('worprees_shop_scripts')) {
    function worprees_shop_scripts() {
        // Main CSS
        wp_enqueue_style('worprees-main', WORPREES_SHOP_URI . '/css/style.css', array(), WORPREES_SHOP_VERSION);
        
        // Google Fonts
        wp_enqueue_style('worprees-google-fonts', 'https://fonts.googleapis.com/css2?family=Vazirmatn:wght@100;200;300;400;500;600;700;800;900&family=IranYekan:wght@100;200;300;400;500;600;700;800;900&display=swap', array(), null);
        
        // Font Awesome
        wp_enqueue_style('worprees-fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', array(), '6.4.0');
        
        // Animate.css
        wp_enqueue_style('worprees-animate', 'https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css', array(), '4.1.1');
        
        // Slick Carousel
        wp_enqueue_style('worprees-slick', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css', array(), '1.8.1');
        
        // Magnific Popup
        wp_enqueue_style('worprees-magnific', 'https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css', array(), '1.1.0');
        
        // AOS
        wp_enqueue_style('worprees-aos', 'https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css', array(), '2.3.4');
        
        // RTL CSS
        if (is_rtl()) {
            wp_enqueue_style('worprees-rtl', WORPREES_SHOP_URI . '/css/rtl.css', array('worprees-main'), WORPREES_SHOP_VERSION);
        }
        
        // JS Files
        wp_enqueue_script('jquery');
        
        // jQuery Migrate
        wp_enqueue_script('worprees-jquery-migrate', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-migrate/3.4.1/jquery-migrate.min.js', array('jquery'), '3.4.1', true);
        
        // Wow.js
        wp_enqueue_script('worprees-wow', 'https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js', array('jquery'), '1.1.2', true);
        
        // AOS
        wp_enqueue_script('worprees-aos', 'https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js', array('jquery'), '2.3.4', true);
        
        // Slick Carousel
        wp_enqueue_script('worprees-slick', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js', array('jquery'), '1.8.1', true);
        
        // Magnific Popup
        wp_enqueue_script('worprees-magnific', 'https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js', array('jquery'), '1.1.0', true);
        
        // Waypoints
        wp_enqueue_script('worprees-waypoints', 'https://cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.1/jquery.waypoints.min.js', array('jquery'), '4.0.1', true);
        
        // CounterUp
        wp_enqueue_script('worprees-counterup', 'https://cdnjs.cloudflare.com/ajax/libs/Counter-Up/1.0.0/jquery.counterup.min.js', array('jquery', 'worprees-waypoints'), '1.0.0', true);
        
        // Isotope
        wp_enqueue_script('worprees-isotope', 'https://cdnjs.cloudflare.com/ajax/libs/isotope/3.0.6/isotope.pkgd.min.js', array('jquery'), '3.0.6', true);
        
        // Custom scripts
        wp_enqueue_script('worprees-navigation', WORPREES_SHOP_URI . '/js/navigation.js', array('jquery'), WORPREES_SHOP_VERSION, true);
        wp_enqueue_script('worprees-skip-link', WORPREES_SHOP_URI . '/js/skip-link-focus-fix.js', array(), WORPREES_SHOP_VERSION, true);
        wp_enqueue_script('worprees-custom', WORPREES_SHOP_URI . '/js/custom.js', array('jquery'), WORPREES_SHOP_VERSION, true);
        
        // Localize script
        wp_localize_script('worprees-custom', 'worpreesData', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'themeurl' => WORPREES_SHOP_URI,
            'nonce' => wp_create_nonce('worprees_nonce'),
        ));
    }
    add_action('wp_enqueue_scripts', 'worprees_shop_scripts');
}

// WooCommerce support
if (class_exists('WooCommerce')) {
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
    add_filter('woocommerce_enqueue_styles', '__return_empty_array');
}

// Load theme files safely
$theme_files = array(
    '/inc/theme-functions.php',
    '/inc/ajax-functions.php',
    '/inc/woocommerce-functions.php',
);

foreach ($theme_files as $file) {
    $file_path = WORPREES_SHOP_DIR . $file;
    if (file_exists($file_path)) {
        require_once $file_path;
    }
}

// Theme activation
if (!function_exists('worprees_shop_activation')) {
    function worprees_shop_activation() {
        flush_rewrite_rules();
    }
    register_activation_hook(__FILE__, 'worprees_shop_activation');
}

// Theme deactivation
if (!function_exists('worprees_shop_deactivation')) {
    function worprees_shop_deactivation() {
        flush_rewrite_rules();
    }
    register_deactivation_hook(__FILE__, 'worprees_shop_deactivation');
}
