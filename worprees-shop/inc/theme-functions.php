<?php
/**
 * Worprees Shop Theme Functions
 * Utility functions for the theme
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get theme option
 */
if (!function_exists('worprees_get_option')) {
    function worprees_get_option($key, $default = '') {
        return get_theme_mod('worprees_' . $key, $default);
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
        $content = str_replace(array(' ', "\n", "\r", "\t"), ' ', $content);
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
 * Social share links
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
 * Pagination
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
        $excerpt = str_replace(array(' ', "\n", "\r", "\t"), ' ', $excerpt);
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
 * Check if WooCommerce is active
 */
if (!function_exists('worprees_is_woocommerce_active')) {
    function worprees_is_woocommerce_active() {
        return class_exists('WooCommerce');
    }
}

/**
 * Theme logo
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
