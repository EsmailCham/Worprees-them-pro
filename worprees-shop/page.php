<?php
/**
 * The template for displaying all pages
 * 
 * @package Worprees_Shop
 */

get_header();

// Breadcrumbs
if (function_exists('worprees_shop_breadcrumbs') && !is_front_page()) :
    worprees_shop_breadcrumbs();
endif;

echo '<main id="main" class="site-main">';

while (have_posts()) : the_post();
    echo '<article id="post-' . esc_attr(get_the_ID()) . '" ' . post_class('page-content');
    echo '>';
    
    // Page Header
    if (!is_front_page()) :
        echo '<header class="page-header py-5">';
        echo '<div class="container">';
        the_title('<h1 class="page-title">', '</h1>');
        echo '</div>';
        echo '</header>';
    endif;
    
    // Featured Image
    if (has_post_thumbnail() && !is_front_page()) :
        echo '<div class="page-thumbnail">';
        the_post_thumbnail('worprees-shop-banner', array(
            'class' => 'page-image',
            'alt' => get_the_title(),
        ));
        echo '</div>';
    endif;
    
    echo '<div class="container py-5">';
    echo '<div class="page-body">';
    the_content();
    
    // Pagination for page content
    wp_link_pages(array(
        'before' => '<div class="page-links">' . esc_html__('صفحات:', 'worprees-shop'),
        'after' => '</div>',
    ));
    
    // Comments
    if (comments_open() || get_comments_number()) :
        echo '<div class="comments-section">';
        comments_template();
        echo '</div>';
    endif;
    
    echo '</div>';
    echo '</div>';
    
    echo '</article>';
endwhile;

echo '</main>';

get_footer();
