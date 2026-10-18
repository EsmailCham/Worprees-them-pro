<?php
/**
 * Worprees Theme Functions
 * Theme utilities, breadcrumbs, reading time, related posts, helpers
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get theme option
 */
if (!function_exists('worprees_get_option')) {
    function worprees_get_option($key, $default = '') {
        $options = get_option('worprees_theme_options', array());
        return isset($options[$key]) ? $options[$key] : $default;
    }
}

/**
 * Breadcrumb navigation
 */
if (!function_exists('worprees_breadcrumb')) {
    function worprees_breadcrumb() {
        global $post;
        
        $show_on_front = get_option('show_on_front', 'posts');
        $home_label = esc_html__('خانه', 'worprees');
        
        $breadcrumb = array();
        
        // Home
        $breadcrumb[] = '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="' . esc_url(home_url('/')) . '"><span itemprop="title">' . $home_label . '</span></a></li>';
        
        // Single post
        if (is_single() && !is_attachment()) {
            $post_type = get_post_type();
            $post_type_object = get_post_type_object($post_type);
            
            if ($post_type_object && $post_type_object->has_archive && $post_type !== 'post') {
                $breadcrumb[] = '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="' . esc_url(get_post_type_archive_link($post_type)) . '"><span itemprop="title">' . esc_html($post_type_object->labels->name) . '</span></a></li>';
            }
            
            $categories = get_the_category();
            if ($categories) {
                $category = $categories[0];
                $parents = get_category_parents($category->term_id, true, '" rel="v:url" property="v:title" />');
                $parents = str_replace('<a', '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url"', $parents);
                $parents = str_replace('</a>', '</span></a></li>', $parents);
                $breadcrumb[] = $parents;
            }
            
            $breadcrumb[] = '<li class="active"><span itemprop="title">' . esc_html(get_the_title()) . '</span></li>';
            
        } elseif (is_page()) {
            // Page
            $parents = array();
            $parent_id = wp_get_post_parent_id($post->ID);
            
            while ($parent_id) {
                $parent_post = get_post($parent_id);
                $parents[] = '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="' . esc_url(get_permalink($parent_id)) . '"><span itemprop="title">' . esc_html(get_the_title($parent_id)) . '</span></a></li>';
                $parent_id = wp_get_post_parent_id($parent_id);
            }
            
            if (!empty($parents)) {
                $breadcrumb = array_merge($breadcrumb, array_reverse($parents));
            }
            
            $breadcrumb[] = '<li class="active"><span itemprop="title">' . esc_html(get_the_title()) . '</span></li>';
            
        } elseif (is_category()) {
            // Category
            $category = get_queried_object();
            $parents = get_category_parents($category->parent, true, '" rel="v:url" property="v:title" />');
            $parents = str_replace('<a', '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url"', $parents);
            $parents = str_replace('</a>', '</span></a></li>', $parents);
            
            if ($parents) {
                $breadcrumb[] = $parents;
            }
            
            $breadcrumb[] = '<li class="active"><span itemprop="title">' . esc_html(single_cat_title('', false)) . '</span></li>';
            
        } elseif (is_tag()) {
            // Tag
            $breadcrumb[] = '<li class="active"><span itemprop="title">' . esc_html__('برچسب: ', 'worprees') . esc_html(single_tag_title('', false)) . '</span></li>';
            
        } elseif (is_author()) {
            // Author
            $breadcrumb[] = '<li class="active"><span itemprop="title">' . esc_html__('نویسنده: ', 'worprees') . esc_html(get_the_author()) . '</span></li>';
            
        } elseif (is_date()) {
            // Date
            $year = get_the_time('Y');
            $month = get_the_time('F');
            $day = get_the_time('j');
            
            $breadcrumb[] = '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="' . esc_url(get_year_link($year)) . '"><span itemprop="title">' . esc_html($year) . '</span></a></li>';
            
            if (is_month()) {
                $breadcrumb[] = '<li class="active"><span itemprop="title">' . esc_html($month) . '</span></li>';
            }
            
            if (is_day()) {
                $breadcrumb[] = '<li class="active"><span itemprop="title">' . esc_html($day) . '</span></li>';
            }
            
        } elseif (is_post_type_archive()) {
            // Post type archive
            $post_type = get_queried_object();
            $breadcrumb[] = '<li class="active"><span itemprop="title">' . esc_html($post_type->labels->name) . '</span></li>';
            
        } elseif (is_tax()) {
            // Taxonomy
            $taxonomy = get_queried_object();
            $breadcrumb[] = '<li class="active"><span itemprop="title">' . esc_html($taxonomy->name) . '</span></li>';
            
        } elseif (is_search()) {
            // Search
            $breadcrumb[] = '<li class="active"><span itemprop="title">' . esc_html__('جستجوی: ', 'worprees') . esc_html(get_search_query()) . '</span></li>';
            
        } elseif (is_404()) {
            // 404
            $breadcrumb[] = '<li class="active"><span itemprop="title">' . esc_html__('404 - صفحه یافت نشد', 'worprees') . '</span></li>';
        }
        
        $breadcrumb = apply_filters('worprees_breadcrumb_items', $breadcrumb);
        
        $output = '<nav class="worprees-breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">';
        $output .= '<ul class="breadcrumb-list">';
        $output .= implode('', $breadcrumb);
        $output .= '</ul>';
        $output .= '</nav>';
        
        echo $output;
    }
}

/**
 * Reading time calculator
 */
if (!function_exists('worprees_reading_time')) {
    function worprees_reading_time($post_id = null) {
        $post = get_post($post_id);
        if (!$post) {
            return '';
        }
        
        $content = $post->post_content;
        $content = strip_tags($content);
        $content = str_replace(array(' ', '\n', '\r', '\t'), ' ', $content);
        $content = trim($content);
        
        $word_count = str_word_count($content);
        $words_per_minute = 200;
        $reading_time = ceil($word_count / $words_per_minute);
        
        if ($reading_time <= 1) {
            return esc_html__('1 دقیقه', 'worprees');
        }
        
        return $reading_time . ' ' . esc_html__('دقیقه', 'worprees');
    }
}

/**
 * Get related posts
 */
if (!function_exists('worprees_related_posts')) {
    function worprees_related_posts($post_id = null, $limit = 4) {
        $post = get_post($post_id);
        if (!$post) {
            return array();
        }
        
        $categories = get_the_category($post->ID);
        $category_ids = array();
        
        foreach ($categories as $category) {
            $category_ids[] = $category->term_id;
        }
        
        $tags = get_the_tags($post->ID);
        $tag_ids = array();
        
        foreach ($tags as $tag) {
            $tag_ids[] = $tag->term_id;
        }
        
        $args = array(
            'post_type' => 'post',
            'posts_per_page' => $limit,
            'post__not_in' => array($post->ID),
            'orderby' => 'rand',
            'tax_query' => array(
                'relation' => 'OR',
                array(
                    'taxonomy' => 'category',
                    'field' => 'term_id',
                    'terms' => $category_ids,
                ),
                array(
                    'taxonomy' => 'post_tag',
                    'field' => 'term_id',
                    'terms' => $tag_ids,
                ),
            ),
        );
        
        $query = new WP_Query($args);
        
        if ($query->have_posts()) {
            return $query->get_posts();
        }
        
        // Fallback to recent posts
        $args = array(
            'post_type' => 'post',
            'posts_per_page' => $limit,
            'post__not_in' => array($post->ID),
            'orderby' => 'date',
            'order' => 'DESC',
        );
        
        $query = new WP_Query($args);
        return $query->have_posts() ? $query->get_posts() : array();
    }
}

/**
 * Get post views count
 */
if (!function_exists('worprees_get_post_views')) {
    function worprees_get_post_views($post_id = null) {
        $post_id = $post_id ? $post_id : get_the_ID();
        $count = get_post_meta($post_id, '_worprees_post_views', true);
        return $count ? (int) $count : 0;
    }
}

/**
 * Set post views count
 */
if (!function_exists('worprees_set_post_views')) {
    function worprees_set_post_views($post_id = null) {
        $post_id = $post_id ? $post_id : get_the_ID();
        $count = worprees_get_post_views($post_id);
        $count++;
        update_post_meta($post_id, '_worprees_post_views', $count);
    }
}

/**
 * Track post views
 */
if (!function_exists('worprees_track_post_views')) {
    function worprees_track_post_views($post_id) {
        if (!is_single() || is_user_logged_in() || is_preview()) {
            return;
        }
        
        if (isset($_COOKIE['worprees_post_views_' . $post_id])) {
            return;
        }
        
        setcookie('worprees_post_views_' . $post_id, '1', time() + 86400, '/');
        worprees_set_post_views($post_id);
    }
    add_action('wp', 'worprees_track_post_views');
}

/**
 * Get social share links
 */
if (!function_exists('worprees_social_share')) {
    function worprees_social_share($post_id = null) {
        $post = get_post($post_id);
        if (!$post) {
            return '';
        }
        
        $url = get_permalink($post->ID);
        $title = urlencode(get_the_title($post->ID));
        $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
        $image_url = $image ? urlencode($image[0]) : '';
        
        $social_networks = array(
            'telegram' => array(
                'label' => esc_html__('تلگرام', 'worprees'),
                'url' => 'https://telegram.me/share/url?url=' . urlencode($url) . '&text=' . $title,
                'icon' => 'fab fa-telegram-plane',
            ),
            'twitter' => array(
                'label' => esc_html__('تویتر', 'worprees'),
                'url' => 'https://twitter.com/intent/tweet?url=' . urlencode($url) . '&text=' . $title,
                'icon' => 'fab fa-twitter',
            ),
            'facebook' => array(
                'label' => esc_html__('فیسبوک', 'worprees'),
                'url' => 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode($url),
                'icon' => 'fab fa-facebook-f',
            ),
            'linkedin' => array(
                'label' => esc_html__('لینکدین', 'worprees'),
                'url' => 'https://www.linkedin.com/shareArticle?mini=true&url=' . urlencode($url) . '&title=' . $title . '&summary=' . urlencode(wp_strip_all_tags(get_the_excerpt($post->ID))),
                'icon' => 'fab fa-linkedin-in',
            ),
            'whatsapp' => array(
                'label' => esc_html__('واتساپ', 'worprees'),
                'url' => 'https://wa.me/?text=' . urlencode($title . ' - ' . $url),
                'icon' => 'fab fa-whatsapp',
            ),
        );
        
        $social_networks = apply_filters('worprees_social_share_networks', $social_networks);
        
        $output = '<div class="social-share">';
        foreach ($social_networks as $key => $network) {
            $output .= '<a href="' . esc_url($network['url']) . '" target="_blank" rel="noopener noreferrer" class="social-share-' . esc_attr($key) . '" title="' . esc_attr($network['label']) . '">';
            $output .= '<i class="' . esc_attr($network['icon']) . '"></i>';
            $output .= '</a>';
        }
        $output .= '</div>';
        
        return $output;
    }
}

/**
 * Get pagination
 */
if (!function_exists('worprees_pagination')) {
    function worprees_pagination($query = null) {
        global $wp_query;
        
        $query = $query ? $query : $wp_query;
        $big = 999999999;
        
        $pages = paginate_links(array(
            'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
            'format' => '?paged=%#%',
            'current' => max(1, get_query_var('paged')),
            'total' => $query->max_num_pages,
            'prev_text' => esc_html__('قبلی', 'worprees'),
            'next_text' => esc_html__('بعدی', 'worprees'),
            'type' => 'array',
            'add_args' => false,
        ));
        
        if (is_array($pages)) {
            $output = '<nav class="worprees-pagination">';
            $output .= '<ul class="pagination-list">';
            foreach ($pages as $page) {
                $output .= '<li class="pagination-item">' . $page . '</li>';
            }
            $output .= '</ul>';
            $output .= '</nav>';
            return $output;
        }
        
        return '';
    }
}

/**
 * Get excerpt with custom length
 */
if (!function_exists('worprees_excerpt')) {
    function worprees_excerpt($length = 55, $post_id = null) {
        $post = get_post($post_id);
        if (!$post) {
            return '';
        }
        
        $excerpt = $post->post_excerpt ? $post->post_excerpt : $post->post_content;
        $excerpt = strip_shortcodes($excerpt);
        $excerpt = strip_tags($excerpt);
        $excerpt = str_replace(array(' ', '\n', '\r', '\t'), ' ', $excerpt);
        $excerpt = trim($excerpt);
        
        if (strlen($excerpt) > $length) {
            $excerpt = substr($excerpt, 0, $length);
            $excerpt = substr($excerpt, 0, strrpos($excerpt, ' ')) . '...';
        }
        
        return esc_html($excerpt);
    }
}

/**
 * Get featured image URL
 */
if (!function_exists('worprees_featured_image_url')) {
    function worprees_featured_image_url($size = 'full', $post_id = null) {
        $post_id = $post_id ? $post_id : get_the_ID();
        $image = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), $size);
        return $image ? $image[0] : '';
    }
}

/**
 * Get first image from content
 */
if (!function_exists('worprees_first_image')) {
    function worprees_first_image($content = null, $size = 'full') {
        $post = get_post();
        $content = $content ? $content : $post->post_content;
        
        preg_match('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $content, $matches);
        
        if (isset($matches[1])) {
            return $matches[1];
        }
        
        return '';
    }
}

/**
 * Check if current page is WooCommerce page
 */
if (!function_exists('worprees_is_woocommerce_page')) {
    function worprees_is_woocommerce_page() {
        if (function_exists('is_woocommerce') && is_woocommerce()) {
            return true;
        }
        
        if (function_exists('is_cart') && is_cart()) {
            return true;
        }
        
        if (function_exists('is_checkout') && is_checkout()) {
            return true;
        }
        
        if (function_exists('is_account_page') && is_account_page()) {
            return true;
        }
        
        return false;
    }
}

/**
 * Get theme logo
 */
if (!function_exists('worprees_logo')) {
    function worprees_logo($echo = true) {
        $custom_logo_id = get_theme_mod('custom_logo');
        $logo = wp_get_attachment_image_src($custom_logo_id, 'full');
        
        if ($logo) {
            $html = '<img src="' . esc_url($logo[0]) . '" alt="' . esc_attr(get_bloginfo('name')) . '" class="worprees-logo">';
        } else {
            $html = '<h1 class="worprees-logo-text">' . esc_html(get_bloginfo('name')) . '</h1>';
        }
        
        if ($echo) {
            echo $html;
        }
        
        return $html;
    }
}

/**
 * Get theme favicon
 */
if (!function_exists('worprees_favicon')) {
    function worprees_favicon() {
        $favicon = get_theme_mod('worprees_favicon');
        if ($favicon) {
            echo '<link rel="icon" type="image/png" href="' . esc_url($favicon) . '">';
        }
    }
    add_action('wp_head', 'worprees_favicon');
}

/**
 * Get theme preloader
 */
if (!function_exists('worprees_preloader')) {
    function worprees_preloader() {
        $preloader = worprees_get_option('preloader', '1');
        if ($preloader) {
            echo '<div class="worprees-preloader"><div class="worprees-preloader-content"><div class="worprees-preloader-spinner"></div></div></div>';
        }
    }
    add_action('wp_body_open', 'worprees_preloader');
}

/**
 * Get back to top button
 */
if (!function_exists('worprees_back_to_top')) {
    function worprees_back_to_top() {
        $back_to_top = worprees_get_option('back_to_top', '1');
        if ($back_to_top) {
            echo '<button class="worprees-back-to-top" id="worpreesBackToTop"><i class="fas fa-chevron-up"></i></button>';
        }
    }
    add_action('wp_footer', 'worprees_back_to_top');
}

/**
 * Get scroll to top script
 */
if (!function_exists('worprees_scroll_to_top_script')) {
    function worprees_scroll_to_top_script() {
        echo '<script>jQuery(document).ready(function($){$(window).scroll(function(){if($(this).scrollTop()>100){$("#worpreesBackToTop").fadeIn();}else{$("#worpreesBackToTop").fadeOut();}});$("#worpreesBackToTop").click(function(){$("html,body").animate({scrollTop:0},800);return false;});});</script>';
    }
    add_action('wp_footer', 'worprees_scroll_to_top_script');
}

/**
 * Get theme version
 */
if (!function_exists('worprees_theme_version')) {
    function worprees_theme_version() {
        $theme = wp_get_theme();
        return $theme->get('Version');
    }
}

/**
 * Get theme author
 */
if (!function_exists('worprees_theme_author')) {
    function worprees_theme_author() {
        $theme = wp_get_theme();
        return $theme->get('Author');
    }
}

/**
 * Get theme description
 */
if (!function_exists('worprees_theme_description')) {
    function worprees_theme_description() {
        $theme = wp_get_theme();
        return $theme->get('Description');
    }
}

/**
 * Get theme tags
 */
if (!function_exists('worprees_theme_tags')) {
    function worprees_theme_tags() {
        $theme = wp_get_theme();
        return $theme->get('Tags');
    }
}

/**
 * Get theme screenshot URL
 */
if (!function_exists('worprees_theme_screenshot')) {
    function worprees_theme_screenshot() {
        $theme = wp_get_theme();
        return $theme->get_screenshot();
    }
}

/**
 * Enqueue theme styles
 */
if (!function_exists('worprees_enqueue_styles')) {
    function worprees_enqueue_styles() {
        // Main style
        wp_enqueue_style('worprees-style', get_stylesheet_uri(), array(), worprees_theme_version());
        
        // RTL style
        if (is_rtl()) {
            wp_enqueue_style('worprees-rtl', get_template_directory_uri() . '/css/rtl.css', array('worprees-style'), worprees_theme_version());
        }
        
        // Google Fonts
        wp_enqueue_style('worprees-google-fonts', 'https://fonts.googleapis.com/css2?family=Vazirmatn:wght@100;200;300;400;500;600;700;800;900&display=swap', array(), null);
        
        // Font Awesome
        wp_enqueue_style('worprees-font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', array(), '6.4.0');
        
        // Animate.css
        wp_enqueue_style('worprees-animate', 'https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css', array(), '4.1.1');
        
        // Slick Carousel
        wp_enqueue_style('worprees-slick', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css', array(), '1.8.1');
        
        // Magnific Popup
        wp_enqueue_style('worprees-magnific', 'https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css', array(), '1.1.0');
        
        // AOS
        wp_enqueue_style('worprees-aos', 'https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css', array(), '2.3.4');
    }
    add_action('wp_enqueue_scripts', 'worprees_enqueue_styles');
}

/**
 * Enqueue theme scripts
 */
if (!function_exists('worprees_enqueue_scripts')) {
    function worprees_enqueue_scripts() {
        // jQuery
        wp_enqueue_script('jquery');
        
        // jQuery Migrate
        wp_enqueue_script('jquery-migrate', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-migrate/3.4.1/jquery-migrate.min.js', array('jquery'), '3.4.1', true);
        
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
        wp_enqueue_script('worprees-custom', get_template_directory_uri() . '/js/custom.js', array('jquery'), worprees_theme_version(), true);
        
        // Navigation
        wp_enqueue_script('worprees-navigation', get_template_directory_uri() . '/js/navigation.js', array('jquery'), worprees_theme_version(), true);
        
        // Skip link focus fix
        wp_enqueue_script('worprees-skip-link', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), worprees_theme_version(), true);
        
        // Localize script
        wp_localize_script('worprees-custom', 'worprees', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('worprees_nonce'),
            'theme_url' => get_template_directory_uri(),
        ));
    }
    add_action('wp_enqueue_scripts', 'worprees_enqueue_scripts');
}

/**
 * Register widget areas
 */
if (!function_exists('worprees_widgets_init')) {
    function worprees_widgets_init() {
        register_sidebar(array(
            'name' => esc_html__('نوار کناری', 'worprees'),
            'id' => 'sidebar',
            'description' => esc_html__('ابزارک های نوار کناری', 'worprees'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ));
        
        register_sidebar(array(
            'name' => esc_html__('فوتر 1', 'worprees'),
            'id' => 'footer-1',
            'description' => esc_html__('ابزارک های فوتر ستون اول', 'worprees'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ));
        
        register_sidebar(array(
            'name' => esc_html__('فوتر 2', 'worprees'),
            'id' => 'footer-2',
            'description' => esc_html__('ابزارک های فوتر ستون دوم', 'worprees'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ));
        
        register_sidebar(array(
            'name' => esc_html__('فوتر 3', 'worprees'),
            'id' => 'footer-3',
            'description' => esc_html__('ابزارک های فوتر ستون سوم', 'worprees'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ));
        
        register_sidebar(array(
            'name' => esc_html__('فوتر 4', 'worprees'),
            'id' => 'footer-4',
            'description' => esc_html__('ابزارک های فوتر ستون چهارم', 'worprees'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ));
        
        register_sidebar(array(
            'name' => esc_html__('نوار کناری فروشگاه', 'worprees'),
            'id' => 'shop-sidebar',
            'description' => esc_html__('ابزارک های نوار کناری فروشگاه', 'worprees'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ));
    }
    add_action('widgets_init', 'worprees_widgets_init');
}

/**
 * Theme setup
 */
if (!function_exists('worprees_setup')) {
    function worprees_setup() {
        // Text domain
        load_theme_textdomain('worprees', get_template_directory() . '/languages');
        
        // Title tag
        add_theme_support('title-tag');
        
        // Post thumbnails
        add_theme_support('post-thumbnails');
        
        // Custom logo
        add_theme_support('custom-logo', array(
            'height' => 100,
            'width' => 300,
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
        
        // Post formats
        add_theme_support('post-formats', array(
            'aside',
            'image',
            'video',
            'quote',
            'link',
            'gallery',
            'audio',
        ));
        
        // Automatic feed links
        add_theme_support('automatic-feed-links');
        
        // Custom background
        add_theme_support('custom-background', array(
            'default-color' => 'ffffff',
        ));
        
        // Custom header
        add_theme_support('custom-header', array(
            'default-image' => '',
            'default-text-color' => '000000',
            'width' => 1920,
            'height' => 400,
            'flex-height' => true,
            'flex-width' => true,
        ));
        
        // WooCommerce support
        add_theme_support('woocommerce');
        
        // Editor styles
        add_theme_support('editor-styles');
        
        // Align wide
        add_theme_support('align-wide');
        
        // Responsive embeds
        add_theme_support('responsive-embeds');
        
        // Register navigation menus
        register_nav_menus(array(
            'primary' => esc_html__('منوی اصلی', 'worprees'),
            'topbar' => esc_html__('منوی بالای سربرگ', 'worprees'),
            'mobile' => esc_html__('منوی موبایل', 'worprees'),
            'footer' => esc_html__('منوی فوتر', 'worprees'),
        ));
        
        // Image sizes
        add_image_size('worprees-thumbnail', 300, 200, true);
        add_image_size('worprees-medium', 600, 400, true);
        add_image_size('worprees-large', 1200, 800, true);
        add_image_size('worprees-hero', 1920, 800, true);
        add_image_size('worprees-product', 400, 400, true);
        
        // Body open hook
        add_action('wp_body_open', 'worprees_preloader');
    }
    add_action('after_setup_theme', 'worprees_setup');
}

/**
 * Theme activation
 */
if (!function_exists('worprees_activation')) {
    function worprees_activation() {
        // Set default options
        $default_options = array(
            'preloader' => '1',
            'back_to_top' => '1',
            'sticky_header' => '1',
            'dark_mode' => '0',
            'rtl' => '1',
        );
        
        update_option('worprees_theme_options', $default_options);
        
        // Flush rewrite rules
        flush_rewrite_rules();
        
        // Set front page
        $front_page = get_page_by_title('خانه');
        if ($front_page) {
            update_option('show_on_front', 'page');
            update_option('page_on_front', $front_page->ID);
        }
    }
    register_activation_hook(__FILE__, 'worprees_activation');
}

/**
 * Theme deactivation
 */
if (!function_exists('worprees_deactivation')) {
    function worprees_deactivation() {
        // Flush rewrite rules
        flush_rewrite_rules();
    }
    register_deactivation_hook(__FILE__, 'worprees_deactivation');
}
