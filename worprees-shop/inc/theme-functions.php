<?php
/**
 * Worprees Shop Theme Functions
 * Helper functions and utilities
 */

if (!defined('ABSPATH')) {
    exit;
}

// Primary menu fallback
if (!function_exists('worprees_shop_primary_menu_fallback')) {
    function worprees_shop_primary_menu_fallback() {
        echo '<ul class="nav-menu">';
        echo '<li><a href="' . esc_url(home_url('/')) . '">2a35412d47 273544cc</a></li>';
        if (class_exists('WooCommerce')) {
            echo '<li><a href="' . esc_url(get_permalink(wc_get_page_id('shop'))) . '">41314834af2747</a></li>';
        }
        echo '</ul>';
    }
}

// Mobile menu fallback
if (!function_exists('worprees_shop_mobile_menu_fallback')) {
    function worprees_shop_mobile_menu_fallback() {
        echo '<ul class="mobile-menu">';
        echo '<li><a href="' . esc_url(home_url('/')) . '">2a35412d47 273544cc</a></li>';
        if (class_exists('WooCommerce')) {
            echo '<li><a href="' . esc_url(get_permalink(wc_get_page_id('shop'))) . '">41314834af2747</a></li>';
        }
        echo '</ul>';
    }
}

// Breadcrumb
if (!function_exists('worprees_shop_breadcrumb')) {
    function worprees_shop_breadcrumb() {
        if (!is_home() && !is_front_page()) {
            echo '<nav class="breadcrumb">';
            echo '<a href="' . esc_url(home_url('/')) . '">2a35412d47 273544cc</a>';
            
            if (is_category() || is_single()) {
                $category = get_the_category();
                if ($category) {
                    echo '<span class="breadcrumb-separator"><i class="fas fa-chevron-left"></i></span>';
                    echo '<a href="' . esc_url(get_category_link($category[0]->term_id)) . '">' . esc_html($category[0]->name) . '</a>';
                }
                if (is_single()) {
                    echo '<span class="breadcrumb-separator"><i class="fas fa-chevron-left"></i></span>';
                    echo '<span>' . esc_html(get_the_title()) . '</span>';
                }
            } elseif (is_page()) {
                echo '<span class="breadcrumb-separator"><i class="fas fa-chevron-left"></i></span>';
                echo '<span>' . esc_html(get_the_title()) . '</span>';
            } elseif (class_exists('WooCommerce') && is_shop()) {
                echo '<span class="breadcrumb-separator"><i class="fas fa-chevron-left"></i></span>';
                echo '<span>41314834af2747</span>';
            }
            
            echo '</nav>';
        }
    }
}

// Estimated reading time
if (!function_exists('worprees_shop_reading_time')) {
    function worprees_shop_reading_time($content = '') {
        if (empty($content)) {
            $content = get_the_content();
        }
        $word_count = str_word_count(strip_tags($content));
        $reading_time = ceil($word_count / 200);
        return $reading_time . ' 2f42cc4247';
    }
}

// Get post views
if (!function_exists('worprees_shop_get_post_views')) {
    function worprees_shop_get_post_views($post_id = '') {
        if (empty($post_id)) {
            global $post;
            $post_id = $post->ID;
        }
        $views = get_post_meta($post_id, 'post_views', true);
        return $views ? $views : 0;
    }
}

// Set post views
if (!function_exists('worprees_shop_set_post_views')) {
    function worprees_shop_set_post_views($post_id = '') {
        if (empty($post_id)) {
            global $post;
            $post_id = $post->ID;
        }
        $views = get_post_meta($post_id, 'post_views', true);
        $views = $views ? $views + 1 : 1;
        update_post_meta($post_id, 'post_views', $views);
    }
}

// Related posts
if (!function_exists('worprees_shop_related_posts')) {
    function worprees_shop_related_posts($post_id = '', $count = 3) {
        if (empty($post_id)) {
            global $post;
            $post_id = $post->ID;
        }
        
        $categories = get_the_category($post_id);
        $category_ids = array();
        foreach ($categories as $category) {
            $category_ids[] = $category->term_id;
        }
        
        $args = array(
            'post_type' => 'post',
            'post__not_in' => array($post_id),
            'posts_per_page' => $count,
            'category__in' => $category_ids,
            'orderby' => 'rand',
        );
        
        $query = new WP_Query($args);
        
        if ($query->have_posts()) {
            echo '<div class="related-posts">';
            echo '<h3 class="related-posts-title">4542274447 2847 274745cc2a46 282e482746cc2f</h3>';
            echo '<div class="related-posts-grid">';
            
            while ($query->have_posts()) {
                $query->the_post();
                echo '<article class="related-post">';
                echo '<a href="' . esc_url(get_permalink()) . '" class="related-post-link">';
                if (has_post_thumbnail()) {
                    echo '<div class="related-post-thumbnail">';
                    the_post_thumbnail('worprees-thumbnail');
                    echo '</div>';
                }
                echo '<h4 class="related-post-title">' . esc_html(get_the_title()) . '</h4>';
                echo '</a>';
                echo '</article>';
            }
            
            echo '</div>';
            echo '</div>';
        }
        wp_reset_postdata();
    }
}

// Social share buttons
if (!function_exists('worprees_shop_social_share')) {
    function worprees_shop_social_share() {
        $url = urlencode(get_permalink());
        $title = urlencode(get_the_title());
        
        echo '<div class="social-share">';
        echo '<span class="social-share-title">27462a342731 28a946cc2f:</span>';
        echo '<a href="https://telegram.me/share/url?url=' . $url . '&text=' . $title . '" target="_blank" class="social-share-link telegram"><i class="fab fa-telegram-plane"></i></a>';
        echo '<a href="https://www.instagram.com/" target="_blank" class="social-share-link instagram"><i class="fab fa-instagram"></i></a>';
        echo '<a href="https://wa.me/?text=' . $url . '" target="_blank" class="social-share-link whatsapp"><i class="fab fa-whatsapp"></i></a>';
        echo '</div>';
    }
}

// Pagination
if (!function_exists('worprees_shop_pagination')) {
    function worprees_shop_pagination() {
        global $wp_query;
        
        if ($wp_query->max_num_pages > 1) {
            echo '<nav class="pagination">';
            
            echo paginate_links(array(
                'base' => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
                'format' => '?paged=%#%',
                'current' => max(1, get_query_var('paged')),
                'total' => $wp_query->max_num_pages,
                'prev_text' => '<i class="fas fa-chevron-right"></i> 2c2f47',
                'next_text' => '28392f <i class="fas fa-chevron-left"></i>',
                'type' => 'list',
            ));
            
            echo '</nav>';
        }
    }
}

// Post meta
if (!function_exists('worprees_shop_post_meta')) {
    function worprees_shop_post_meta() {
        echo '<div class="post-meta">';
        
        echo '<span class="post-date">';
        echo '<i class="far fa-calendar-alt"></i>';
        echo '<time datetime="' . esc_attr(get_the_date('c')) . '">' . esc_html(get_the_date()) . '</time>';
        echo '</span>';
        
        echo '<span class="post-author">';
        echo '<i class="far fa-user"></i>';
        echo '<a href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a>';
        echo '</span>';
        
        if (has_category()) {
            echo '<span class="post-categories">';
            echo '<i class="far fa-folder"></i>';
            the_category(', ');
            echo '</span>';
        }
        
        if (comments_open() || get_comments_number()) {
            echo '<span class="post-comments">';
            echo '<i class="far fa-comment"></i>';
            comments_popup_link('0 2f2faf4832', '1 2f2faf4832', '% 2f2faf4832');
            echo '</span>';
        }
        
        echo '<span class="post-views">';
        echo '<i class="far fa-eye"></i>';
        echo esc_html(worprees_shop_get_post_views()) . ' 2f2f282731';
        echo '</span>';
        
        echo '</div>';
    }
}

// Post tags
if (!function_exists('worprees_shop_post_tags')) {
    function worprees_shop_post_tags() {
        if (has_tag()) {
            echo '<div class="post-tags">';
            echo '<span class="tags-title">28318633282f:</span>';
            the_tags('<ul class="tags-list"><li>', '</li><li>', '</li></ul>');
            echo '</div>';
        }
    }
}

// Author box
if (!function_exists('worprees_shop_author_box')) {
    function worprees_shop_author_box() {
        echo '<div class="author-box">';
        echo '<div class="author-avatar">';
        echo get_avatar(get_the_author_meta('ID'), 100);
        echo '</div>';
        echo '<div class="author-info">';
        echo '<h3 class="author-name">2746322745: ' . esc_html(get_the_author()) . '</h3>';
        echo '<p class="author-description">' . wp_kses_post(get_the_author_meta('description')) . '</p>';
        echo '</div>';
        echo '</div>';
    }
}

// Newsletter form
if (!function_exists('worprees_shop_newsletter_form')) {
    function worprees_shop_newsletter_form() {
        echo '<form class="newsletter-form">';
        echo '<input type="email" class="newsletter-email" placeholder="27cc45cc44 2e482f" required>';
        echo '<button type="submit" class="newsletter-submit">2b2e2f48 27342a3127a9</button>';
        echo '<div class="newsletter-message"></div>';
        echo '</form>';
    }
}

// Brand logos
if (!function_exists('worprees_shop_brand_logos')) {
    function worprees_shop_brand_logos() {
        $brands = array(
            'brand1.png',
            'brand2.png',
            'brand3.png',
            'brand4.png',
            'brand5.png',
        );
        
        echo '<div class="brands-section">';
        echo '<div class="brands-slider">';
        
        foreach ($brands as $brand) {
            $brand_url = WORPREES_SHOP_URI . '/images/brands/' . $brand;
            echo '<div class="brand-logo">';
            echo '<img src="' . esc_url($brand_url) . '" alt="2831462f">';
            echo '</div>';
        }
        
        echo '</div>';
        echo '</div>';
    }
}

// Custom logo for login page
if (!function_exists('worprees_shop_custom_login_logo')) {
    function worprees_shop_custom_login_logo() {
        echo '<style type="text/css">';
        echo 'h1 a { background-image: url("' . esc_url(WORPREES_SHOP_URI) . '/images/logo.png") !important; }';
        echo '</style>';
    }
    add_action('login_head', 'worprees_shop_custom_login_logo');
}

// Custom login URL
if (!function_exists('worprees_shop_custom_login_url')) {
    function worprees_shop_custom_login_url($url) {
        return home_url();
    }
    add_filter('login_headerurl', 'worprees_shop_custom_login_url');
}

// Custom login title
if (!function_exists('worprees_shop_custom_login_title')) {
    function worprees_shop_custom_login_title($title) {
        return get_bloginfo('name');
    }
    add_filter('login_headertitle', 'worprees_shop_custom_login_title');
}
