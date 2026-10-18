<?php
/**
 * Worprees Shop Theme Functions
 * 
 * @package Worprees_Shop
 */

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
if (!function_exists('worprees_shop_setup')) :
    function worprees_shop_setup() {
        // Make theme available for translation
        load_theme_textdomain('worprees-shop', get_template_directory() . '/languages');
        
        // Add default posts and comments RSS feed links to head
        add_theme_support('automatic-feed-links');
        
        // Let WordPress manage the document title
        add_theme_support('title-tag');
        
        // Enable support for Post Thumbnails
        add_theme_support('post-thumbnails');
        
        // Set post thumbnail size
        set_post_thumbnail_size(800, 600, true);
        
        // Add custom image sizes
        add_image_size('worprees-shop-product', 400, 400, true);
        add_image_size('worprees-shop-product-thumbnail', 200, 200, true);
        add_image_size('worprees-shop-banner', 1400, 600, true);
        
        // Register navigation menus
        register_nav_menus(array(
            'primary' => esc_html__('منوی اصلی', 'worprees-shop'),
            'topbar' => esc_html__('منوی بالای هدر', 'worprees-shop'),
            'footer' => esc_html__('منوی فوتر', 'worprees-shop'),
            'mobile' => esc_html__('منوی موبایل', 'worprees-shop'),
        ));
        
        // Switch default core markup to output valid HTML5
        add_theme_support('html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
            'navigation-widgets',
        ));
        
        // Add theme support for selective refresh for widgets
        add_theme_support('customize-selective-refresh-widgets');
        
        // Add support for core custom logo
        add_theme_support('custom-logo', array(
            'height' => 100,
            'width' => 400,
            'flex-height' => true,
            'flex-width' => true,
            'header-text' => array('site-title', 'site-description'),
        ));
        
        // Add support for custom background
        add_theme_support('custom-background', array(
            'default-color' => 'f5f6fa',
        ));
        
        // Add support for editor styles
        add_theme_support('editor-styles');
        
        // Add support for responsive embeds
        add_theme_support('responsive-embeds');
        
        // Add support for alignment
        add_theme_support('align-wide');
        
        // Add support for block styles
        add_theme_support('wp-block-styles');
    }
endif;
add_action('after_setup_theme', 'worprees_shop_setup');

// Set content width
if (!function_exists('worprees_shop_content_width')) :
    function worprees_shop_content_width() {
        $GLOBALS['content_width'] = apply_filters('worprees_shop_content_width', 1140);
    }
endif;
add_action('after_setup_theme', 'worprees_shop_content_width', 0);

// Register widgets
if (!function_exists('worprees_shop_widgets_init')) :
    function worprees_shop_widgets_init() {
        // Sidebar
        register_sidebar(array(
            'name' => esc_html__('نوار جانبی', 'worprees-shop'),
            'id' => 'sidebar-1',
            'description' => esc_html__('ویجت‌های نوار جانبی را اینجا قرار دهید', 'worprees-shop'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget' => '</section>',
            'before_title' => '<h2 class="widget-title">',
            'after_title' => '</h2>',
        ));
        
        // Footer widgets
        for ($i = 1; $i <= 4; $i++) {
            register_sidebar(array(
                'name' => sprintf(esc_html__('فوتر %d', 'worprees-shop'), $i),
                'id' => 'footer-' . $i,
                'description' => sprintf(esc_html__('ویجت‌های ستون %d فوتر را اینجا قرار دهید', 'worprees-shop'), $i),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget' => '</section>',
                'before_title' => '<h3 class="widget-title">',
                'after_title' => '</h3>',
            ));
        }
        
        // Shop sidebar
        if (class_exists('WooCommerce')) {
            register_sidebar(array(
                'name' => esc_html__('نوار جانبی فروشگاه', 'worprees-shop'),
                'id' => 'shop-sidebar',
                'description' => esc_html__('ویجت‌های نوار جانبی فروشگاه را اینجا قرار دهید', 'worprees-shop'),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget' => '</section>',
                'before_title' => '<h3 class="widget-title">',
                'after_title' => '</h3>',
            ));
        }
    }
endif;
add_action('widgets_init', 'worprees_shop_widgets_init');

// Enqueue scripts and styles
if (!function_exists('worprees_shop_scripts')) :
    function worprees_shop_scripts() {
        // CSS Files
        wp_enqueue_style('worprees-shop-style', WORPREES_SHOP_URI . '/css/style.css', array(), WORPREES_SHOP_VERSION);
        
        // Google Fonts
        wp_enqueue_style('worprees-shop-google-fonts', 'https://fonts.googleapis.com/css2?family=Vazirmatn:wght@100;200;300;400;500;600;700;800;900&family=IranYekan:wght@100;200;300;400;500;600;700;800;900&display=swap', array(), WORPREES_SHOP_VERSION);
        
        // Font Awesome
        wp_enqueue_style('worprees-shop-fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', array(), '6.4.0');
        
        // RTL CSS
        if (is_rtl()) {
            wp_enqueue_style('worprees-shop-rtl', WORPREES_SHOP_URI . '/css/rtl.css', array(), WORPREES_SHOP_VERSION);
        }
        
        // JS Files
        wp_enqueue_script('worprees-shop-navigation', WORPREES_SHOP_URI . '/js/navigation.js', array('jquery'), WORPREES_SHOP_VERSION, true);
        wp_enqueue_script('worprees-shop-skip-link-focus-fix', WORPREES_SHOP_URI . '/js/skip-link-focus-fix.js', array(), WORPREES_SHOP_VERSION, true);
        
        // Custom JS
        wp_enqueue_script('worprees-shop-custom', WORPREES_SHOP_URI . '/js/custom.js', array('jquery'), WORPREES_SHOP_VERSION, true);
        
        // Localize script for AJAX
        wp_localize_script('worprees-shop-custom', 'worpreesShopData', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'themeurl' => WORPREES_SHOP_URI,
            'nonce' => wp_create_nonce('worprees-shop-nonce'),
            'add_to_cart_text' => esc_html__('افزودن به سبد', 'worprees-shop'),
            'added_to_cart_text' => esc_html__('افزوده شد!', 'worprees-shop'),
            'error_message' => esc_html__('خطا رخ داد', 'worprees-shop'),
        ));
    }
endif;
add_action('wp_enqueue_scripts', 'worprees_shop_scripts');

// WooCommerce support
if (class_exists('WooCommerce')) {
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
    
    // Disable WooCommerce default styles
    add_filter('woocommerce_enqueue_styles', '__return_empty_array');
}

// Load theme files
require_once WORPREES_SHOP_DIR . '/inc/theme-functions.php';

// Check if template-tags.php exists before loading
$template_tags_path = WORPREES_SHOP_DIR . '/inc/template-tags.php';
if (file_exists($template_tags_path)) {
    require_once $template_tags_path;
}

// Check if WooCommerce is active
if (!function_exists('worprees_shop_is_woocommerce_active')) :
    function worprees_shop_is_woocommerce_active() {
        return class_exists('WooCommerce');
    }
endif;

// Primary menu fallback
if (!function_exists('worprees_shop_primary_menu_fallback')) :
    function worprees_shop_primary_menu_fallback() {
        echo '<ul class="nav-menu">';
        echo '<li class="menu-item"><a href="' . esc_url(home_url('/')) . '">' . esc_html__('خانه', 'worprees-shop') . '</a></li>';
        
        if (class_exists('WooCommerce')) :
            echo '<li class="menu-item"><a href="' . esc_url(wc_get_page_permalink('shop')) . '">' . esc_html__('فروشگاه', 'worprees-shop') . '</a></li>';
        endif;
        
        if (get_option('page_for_posts')) :
            echo '<li class="menu-item"><a href="' . esc_url(get_permalink(get_option('page_for_posts'))) . '">' . esc_html__('وبلاگ', 'worprees-shop') . '</a></li>';
        endif;
        
        echo '<li class="menu-item"><a href="' . esc_url(wc_get_page_permalink('myaccount')) . '">' . esc_html__('حساب کاربری', 'worprees-shop') . '</a></li>';
        echo '<li class="menu-item"><a href="' . esc_url(wc_get_page_permalink('cart')) . '">' . esc_html__('سبد خرید', 'worprees-shop') . '</a></li>';
        echo '</ul>';
    }
endif;

// Mobile menu fallback
if (!function_exists('worprees_shop_mobile_menu_fallback')) :
    function worprees_shop_mobile_menu_fallback() {
        echo '<ul class="mobile-nav-menu">';
        echo '<li class="menu-item"><a href="' . esc_url(home_url('/')) . '">' . esc_html__('خانه', 'worprees-shop') . '</a></li>';
        
        if (class_exists('WooCommerce')) :
            echo '<li class="menu-item"><a href="' . esc_url(wc_get_page_permalink('shop')) . '">' . esc_html__('فروشگاه', 'worprees-shop') . '</a></li>';
        endif;
        
        if (get_option('page_for_posts')) :
            echo '<li class="menu-item"><a href="' . esc_url(get_permalink(get_option('page_for_posts'))) . '">' . esc_html__('وبلاگ', 'worprees-shop') . '</a></li>';
        endif;
        
        echo '<li class="menu-item"><a href="' . esc_url(wc_get_page_permalink('myaccount')) . '">' . esc_html__('حساب کاربری', 'worprees-shop') . '</a></li>';
        echo '<li class="menu-item"><a href="' . esc_url(wc_get_page_permalink('cart')) . '">' . esc_html__('سبد خرید', 'worprees-shop') . '</a></li>';
        echo '</ul>';
    }
endif;

// Breadcrumbs
if (!function_exists('worprees_shop_breadcrumbs')) :
    function worprees_shop_breadcrumbs() {
        if (is_front_page() || is_home()) {
            return;
        }
        
        echo '<nav class="breadcrumbs py-3" aria-label="' . esc_attr__('مسیر ناوبری', 'worprees-shop') . '">';
        echo '<div class="container">';
        echo '<ul class="breadcrumbs-list">';
        echo '<li><a href="' . esc_url(home_url('/')) . '">' . esc_html__('خانه', 'worprees-shop') . '</a></li>';
        
        if (class_exists('WooCommerce') && (is_shop() || is_product_category() || is_product_tag() || is_product())) :
            echo '<li><a href="' . esc_url(wc_get_page_permalink('shop')) . '">' . esc_html__('فروشگاه', 'worprees-shop') . '</a></li>';
        endif;
        
        if (is_product_category()) {
            $current_term = get_queried_object();
            echo '<li class="current">' . esc_html($current_term->name) . '</li>';
        } elseif (is_product()) {
            echo '<li class="current">' . esc_html(get_the_title()) . '</li>';
        } elseif (is_single()) {
            echo '<li class="current">' . esc_html(get_the_title()) . '</li>';
        } elseif (is_page()) {
            echo '<li class="current">' . esc_html(get_the_title()) . '</li>';
        } elseif (is_search()) {
            echo '<li class="current">' . esc_html__('نتایج جستجو برای: ', 'worprees-shop') . get_search_query() . '</li>';
        }
        
        echo '</ul>';
        echo '</div>';
        echo '</nav>';
    }
endif;

// Calculate reading time
if (!function_exists('worprees_shop_calculate_reading_time')) :
    function worprees_shop_calculate_reading_time($content) {
        $word_count = str_word_count(strip_tags($content));
        $words_per_minute = 200;
        $minutes = ceil($word_count / $words_per_minute);
        
        if ($minutes < 1) {
            return esc_html__('کمتر از 1 دقیقه', 'worprees-shop');
        } elseif ($minutes == 1) {
            return esc_html__('1 دقیقه خواندن', 'worprees-shop');
        } else {
            return sprintf(esc_html__('%d دقیقه خواندن', 'worprees-shop'), $minutes);
        }
    }
endif;

// Get related posts
if (!function_exists('worprees_shop_get_related_posts')) :
    function worprees_shop_get_related_posts($post_id, $count = 3) {
        $tags = wp_get_post_tags($post_id);
        $categories = wp_get_post_categories($post_id);
        
        if ($tags) {
            $tag_ids = array();
            foreach ($tags as $tag) {
                $tag_ids[] = $tag->term_id;
            }
            
            $related_posts = get_posts(array(
                'tag__in' => $tag_ids,
                'post__not_in' => array($post_id),
                'posts_per_page' => $count,
                'orderby' => 'rand',
                'post_status' => 'publish',
            ));
            
            if ($related_posts) {
                return $related_posts;
            }
        }
        
        if ($categories) {
            $related_posts = get_posts(array(
                'category__in' => $categories,
                'post__not_in' => array($post_id),
                'posts_per_page' => $count,
                'orderby' => 'rand',
                'post_status' => 'publish',
            ));
            
            if ($related_posts) {
                return $related_posts;
            }
        }
        
        return get_posts(array(
            'post__not_in' => array($post_id),
            'posts_per_page' => $count,
            'orderby' => 'rand',
            'post_status' => 'publish',
        ));
    }
endif;
