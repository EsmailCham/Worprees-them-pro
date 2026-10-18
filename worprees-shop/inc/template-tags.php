<?php
/**
 * Worprees Shop Template Tags
 * 
 * Contains template tags and helper functions for the theme
 * 
 * @package Worprees_Shop
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Display the post title
 */
if (!function_exists('worprees_shop_post_title')) :
    function worprees_shop_post_title() {
        if (is_single() || is_page()) {
            the_title('<h1 class="entry-title">', '</h1>');
        } else {
            the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
        }
    }
endif;

/**
 * Display the post meta
 */
if (!function_exists('worprees_shop_post_meta')) :
    function worprees_shop_post_meta() {
        echo '<div class="entry-meta">';
        
        // Date
        echo '<span class="posted-on">';
        echo '<a href="' . esc_url(get_permalink()) . '" rel="bookmark">';
        echo '<time class="entry-date published updated" datetime="' . esc_attr(get_the_date('c')) . '">';
        echo '<i class="far fa-calendar-alt"></i> ';
        echo esc_html(get_the_date());
        echo '</time>';
        echo '</a>';
        echo '</span>';
        
        // Author
        echo '<span class="byline">';
        echo '<span class="author vcard">';
        echo '<a class="url fn n" href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">';
        echo '<i class="far fa-user"></i> ';
        echo esc_html(get_the_author());
        echo '</a>';
        echo '</span>';
        echo '</span>';
        
        // Comments
        if (comments_open() || get_comments_number()) {
            echo '<span class="comments-link">';
            comments_popup_link(
                '<i class="far fa-comment"></i> ' . esc_html__('0 8', 'worprees-shop'),
                '<i class="far fa-comment"></i> ' . esc_html__('1 8', 'worprees-shop'),
                '<i class="far fa-comment"></i> ' . esc_html__('% 8', 'worprees-shop'),
                'comments-link',
                ''
            );
            echo '</span>';
        }
        
        // Reading time
        if (function_exists('worprees_shop_calculate_reading_time')) {
            echo '<span class="reading-time">';
            echo '<i class="far fa-clock"></i> ';
            echo esc_html(worprees_shop_calculate_reading_time(get_the_content()));
            echo '</span>';
        }
        
        echo '</div>';
    }
endif;

/**
 * Display the post thumbnail
 */
if (!function_exists('worprees_shop_post_thumbnail')) :
    function worprees_shop_post_thumbnail($size = 'post-thumbnail') {
        if (post_password_required() || is_attachment() || !has_post_thumbnail()) {
            return;
        }
        
        echo '<div class="post-thumbnail">';
        echo '<a href="' . esc_url(get_permalink()) . '">';
        the_post_thumbnail($size, array(
            'alt' => the_title_attribute(array(
                'echo' => false,
            )),
        ));
        echo '</a>';
        echo '</div>';
    }
endif;

/**
 * Display the entry header
 */
if (!function_exists('worprees_shop_entry_header')) :
    function worprees_shop_entry_header() {
        echo '<header class="entry-header">';
        
        if (is_single()) {
            worprees_shop_post_meta();
        }
        
        worprees_shop_post_title();
        
        if (!is_single()) {
            worprees_shop_post_meta();
        }
        
        echo '</header>';
    }
endif;

/**
 * Display the entry content
 */
if (!function_exists('worprees_shop_entry_content')) :
    function worprees_shop_entry_content() {
        echo '<div class="entry-content">';
        
        if (is_single()) {
            the_content();
        } else {
            the_excerpt();
        }
        
        echo '</div>';
    }
endif;

/**
 * Display the entry footer
 */
if (!function_exists('worprees_shop_entry_footer')) :
    function worprees_shop_entry_footer() {
        echo '<footer class="entry-footer">';
        
        // Categories
        if (has_category()) {
            echo '<span class="cat-links">';
            echo '<i class="fas fa-folder-open"></i> ';
            echo esc_html__('8: ', 'worprees-shop');
            echo get_the_category_list(', ');
            echo '</span>';
        }
        
        // Tags
        if (has_tag()) {
            echo '<span class="tags-links">';
            echo '<i class="fas fa-tag"></i> ';
            echo esc_html__('8: ', 'worprees-shop');
            echo get_the_tag_list('', ', ', '');
            echo '</span>';
        }
        
        echo '</footer>';
    }
endif;

/**
 * Display the post navigation
 */
if (!function_exists('worprees_shop_post_navigation')) :
    function worprees_shop_post_navigation() {
        $previous = get_previous_post();
        $next = get_next_post();
        
        if ($previous || $next) {
            echo '<nav class="navigation post-navigation">';
            echo '<h2 class="screen-reader-text">' . esc_html__('8 8', 'worprees-shop') . '</h2>';
            echo '<div class="nav-links">';
            
            if ($previous) {
                echo '<div class="nav-previous">';
                echo '<a href="' . esc_url(get_permalink($previous->ID)) . '" rel="prev">';
                echo '<span class="nav-subtitle">' . esc_html__('9', 'worprees-shop') . '</span>';
                echo '<span class="nav-title">' . esc_html(get_the_title($previous->ID)) . '</span>';
                echo '</a>';
                echo '</div>';
            }
            
            if ($next) {
                echo '<div class="nav-next">';
                echo '<a href="' . esc_url(get_permalink($next->ID)) . '" rel="next">';
                echo '<span class="nav-subtitle">' . esc_html__('8', 'worprees-shop') . '</span>';
                echo '<span class="nav-title">' . esc_html(get_the_title($next->ID)) . '</span>';
                echo '</a>';
                echo '</div>';
            }
            
            echo '</div>';
            echo '</nav>';
        }
    }
endif;

/**
 * Display the pagination
 */
if (!function_exists('worprees_shop_pagination')) :
    function worprees_shop_pagination() {
        global $wp_query;
        
        if ($wp_query->max_num_pages > 1) {
            $big = 999999999;
            
            echo '<nav class="pagination">';
            echo paginate_links(array(
                'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                'format' => '?paged=%#%',
                'current' => max(1, get_query_var('paged')),
                'total' => $wp_query->max_num_pages,
                'prev_text' => esc_html__('8', 'worprees-shop'),
                'next_text' => esc_html__('8', 'worprees-shop'),
                'type' => 'list',
            ));
            echo '</nav>';
        }
    }
endif;

/**
 * Display the social sharing buttons
 */
if (!function_exists('worprees_shop_social_sharing')) :
    function worprees_shop_social_sharing() {
        $social_networks = array(
            'telegram' => array(
                'label' => esc_html__('0', 'worprees-shop'),
                'icon' => 'fab fa-telegram-plane',
                'url' => 'https://telegram.me/share/url?url=' . urlencode(get_permalink()) . '&text=' . urlencode(get_the_title()),
            ),
            'twitter' => array(
                'label' => esc_html__('8', 'worprees-shop'),
                'icon' => 'fab fa-twitter',
                'url' => 'https://twitter.com/intent/tweet?url=' . urlencode(get_permalink()) . '&text=' . urlencode(get_the_title()),
            ),
            'facebook' => array(
                'label' => esc_html__('6', 'worprees-shop'),
                'icon' => 'fab fa-facebook-f',
                'url' => 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode(get_permalink()),
            ),
            'linkedin' => array(
                'label' => esc_html__('0', 'worprees-shop'),
                'icon' => 'fab fa-linkedin-in',
                'url' => 'https://www.linkedin.com/shareArticle?mini=true&url=' . urlencode(get_permalink()) . '&title=' . urlencode(get_the_title()),
            ),
            'whatsapp' => array(
                'label' => esc_html__('7', 'worprees-shop'),
                'icon' => 'fab fa-whatsapp',
                'url' => 'https://wa.me/?text=' . urlencode(get_the_title() . ' - ' . get_permalink()),
            ),
        );
        
        echo '<div class="social-sharing">';
        echo '<span>' . esc_html__('8 0: ', 'worprees-shop') . '</span>';
        
        foreach ($social_networks as $network) {
            echo '<a href="' . esc_url($network['url']) . '" target="_blank" rel="noopener noreferrer" title="' . esc_attr($network['label']) . '">';
            echo '<i class="' . esc_attr($network['icon']) . '"></i>';
            echo '</a>';
        }
        
        echo '</div>';
    }
endif;

/**
 * Display the author bio
 */
if (!function_exists('worprees_shop_author_bio')) :
    function worprees_shop_author_bio() {
        if (is_single() && get_the_author_meta('description')) {
            echo '<div class="author-bio">';
            echo '<div class="author-avatar">';
            echo get_avatar(get_the_author_meta('ID'), 100);
            echo '</div>';
            echo '<div class="author-info">';
            echo '<h3 class="author-name">' . esc_html(get_the_author()) . '</h3>';
            echo '<p class="author-description">' . wp_kses_post(get_the_author_meta('description')) . '</p>';
            echo '</div>';
            echo '</div>';
        }
    }
endif;

/**
 * Display the related posts
 */
if (!function_exists('worprees_shop_related_posts')) :
    function worprees_shop_related_posts() {
        if (is_single() && function_exists('worprees_shop_get_related_posts')) {
            $related_posts = worprees_shop_get_related_posts(get_the_ID(), 3);
            
            if ($related_posts) {
                echo '<div class="related-posts">';
                echo '<h3>' . esc_html__('8 8', 'worprees-shop') . '</h3>';
                echo '<div class="related-posts-grid">';
                
                foreach ($related_posts as $post) {
                    setup_postdata($post);
                    
                    echo '<article class="related-post">';
                    echo '<a href="' . esc_url(get_permalink($post->ID)) . '">';
                    if (has_post_thumbnail($post->ID)) {
                        echo get_the_post_thumbnail($post->ID, 'thumbnail');
                    }
                    echo '<h4>' . esc_html(get_the_title($post->ID)) . '</h4>';
                    echo '</a>';
                    echo '</article>';
                }
                
                wp_reset_postdata();
                
                echo '</div>';
                echo '</div>';
            }
        }
    }
endif;

/**
 * Display the copyright text
 */
if (!function_exists('worprees_shop_copyright')) :
    function worprees_shop_copyright() {
        echo '<div class="site-info">';
        echo '<p>';
        echo esc_html__('8 6 9 8 8 8. 6 8 8 8 8 8.', 'worprees-shop');
        echo '</p>';
        echo '</div>';
    }
endif;

/**
 * Display the back to top button
 */
if (!function_exists('worprees_shop_back_to_top')) :
    function worprees_shop_back_to_top() {
        echo '<button id="back-to-top" class="back-to-top">';
        echo '<i class="fas fa-chevron-up"></i>';
        echo '<span class="screen-reader-text">' . esc_html__('8 8 8', 'worprees-shop') . '</span>';
        echo '</button>';
    }
endif;

/**
 * Display the preloader
 */
if (!function_exists('worprees_shop_preloader')) :
    function worprees_shop_preloader() {
        echo '<div class="site-preloader">';
        echo '<div class="preloader-content">';
        echo '<div class="preloader-spinner"></div>';
        echo '</div>';
        echo '</div>';
    }
endif;

/**
 * Display the mobile menu button
 */
if (!function_exists('worprees_shop_mobile_menu_button')) :
    function worprees_shop_mobile_menu_button() {
        echo '<button id="mobile-menu-button" class="mobile-menu-button">';
        echo '<i class="fas fa-bars"></i>';
        echo '<span class="screen-reader-text">' . esc_html__('8', 'worprees-shop') . '</span>';
        echo '</button>';
    }
endif;

/**
 * Display the search button
 */
if (!function_exists('worprees_shop_search_button')) :
    function worprees_shop_search_button() {
        echo '<button id="search-button" class="search-button">';
        echo '<i class="fas fa-search"></i>';
        echo '<span class="screen-reader-text">' . esc_html__('8', 'worprees-shop') . '</span>';
        echo '</button>';
    }
endif;

/**
 * Display the search form
 */
if (!function_exists('worprees_shop_search_form')) :
    function worprees_shop_search_form() {
        echo '<div class="search-form-container">';
        echo '<form role="search" method="get" class="search-form" action="' . esc_url(home_url('/')) . '">';
        echo '<label>';
        echo '<span class="screen-reader-text">' . esc_html__('8 8', 'worprees-shop') . '</span>';
        echo '<input type="search" class="search-field" placeholder="' . esc_attr__('8 8...', 'worprees-shop') . '" value="' . get_search_query() . '" name="s" />';
        echo '</label>';
        echo '<button type="submit" class="search-submit">';
        echo '<i class="fas fa-search"></i>';
        echo '<span class="screen-reader-text">' . esc_html__('8', 'worprees-shop') . '</span>';
        echo '</button>';
        echo '</form>';
        echo '</div>';
    }
endif;

/**
 * Display the newsletter signup form
 */
if (!function_exists('worprees_shop_newsletter_form')) :
    function worprees_shop_newsletter_form() {
        echo '<div class="newsletter-form">';
        echo '<form class="newsletter-form-inner">';
        echo '<input type="email" placeholder="' . esc_attr__('8 7', 'worprees-shop') . '" required />';
        echo '<button type="submit"><i class="fas fa-paper-plane"></i></button>';
        echo '</form>';
        echo '</div>';
    }
endif;

/**
 * Display the site logo
 */
if (!function_exists('worprees_shop_site_logo')) :
    function worprees_shop_site_logo() {
        if (has_custom_logo()) {
            the_custom_logo();
        } else {
            echo '<h1 class="site-title">';
            echo '<a href="' . esc_url(home_url('/')) . '" rel="home">';
            echo esc_html(get_bloginfo('name'));
            echo '</a>';
            echo '</h1>';
            
            $description = get_bloginfo('description', 'display');
            if ($description || is_customize_preview()) {
                echo '<p class="site-description">' . esc_html($description) . '</p>';
            }
        }
    }
endif;

/**
 * Display the site branding
 */
if (!function_exists('worprees_shop_site_branding')) :
    function worprees_shop_site_branding() {
        echo '<div class="site-branding">';
        worprees_shop_site_logo();
        echo '</div>';
    }
endif;
