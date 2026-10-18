<?php
/**
 * The template for displaying search results
 * 
 * @package Worprees_Shop
 */

get_header();

// Breadcrumbs
if (function_exists('worprees_shop_breadcrumbs')) :
    worprees_shop_breadcrumbs();
endif;

echo '<main id="main" class="site-main">';

// Search Results Header
echo '<header class="search-results-header py-5">';
echo '<div class="container">';
echo '<h1>' . sprintf(esc_html__('نتایج جستجو برای: %s', 'worprees-shop'), get_search_query()) . '</h1>';
echo '</div>';
echo '</header>';

// Search Form
echo '<div class="search-results-form py-4">';
echo '<div class="container">';
echo '<form role="search" method="get" class="search-form" action="' . esc_url(home_url('/')) . '">';
echo '<label for="search-input" class="screen-reader-text">' . esc_html__('جستجو:', 'worprees-shop') . '</label>';
echo '<input type="search" id="search-input" class="search-field" placeholder="' . esc_attr__('جستجو...', 'worprees-shop') . '" value="' . get_search_query() . '" name="s">';
echo '<button type="submit" class="search-submit"><i class="fas fa-search"></i></button>';
echo '</form>';
echo '</div>';
echo '</div>';

// Search Results
echo '<section class="search-results py-5">';
echo '<div class="container">';

if (have_posts()) :
    echo '<div class="search-results-list">';
    
    while (have_posts()) : the_post();
        echo '<article class="search-result-item">';
        
        // Featured Image
        if (has_post_thumbnail()) :
            echo '<div class="search-result-thumbnail">';
            echo '<a href="' . esc_url(get_permalink()) . '">';
            the_post_thumbnail('worprees-shop-product-thumbnail');
            echo '</a>';
            echo '</div>';
        endif;
        
        echo '<div class="search-result-content">';
        echo '<h3><a href="' . esc_url(get_permalink()) . '">' . esc_html(get_the_title()) . '</a></h3>';
        echo '<p>' . wp_kses_post(wpautop(get_the_excerpt())) . '</p>';
        echo '<a href="' . esc_url(get_permalink()) . '" class="btn btn-outline">' . esc_html__('ادامه مطلب', 'worprees-shop') . '</a>';
        echo '</div>';
        echo '</article>';
    endwhile;
    
    echo '</div>';
    
    // Pagination
    echo '<div class="pagination">';
    echo paginate_links(array(
        'prev_text' => '<i class="fas fa-chevron-right"></i>',
        'next_text' => '<i class="fas fa-chevron-left"></i>',
        'type' => 'list',
    ));
    echo '</div>';
    
else :
    echo '<div class="no-results text-center py-5">';
    echo '<h2>' . esc_html__('هیچ نتیجه ای یافت نشد', 'worprees-shop') . '</h2>';
    echo '<p>' . sprintf(esc_html__('هیچ نتیجه ای برای "%s" یافت نشد', 'worprees-shop'), get_search_query()) . '</p>';
    echo '</div>';
endif;

echo '</div>';
echo '</section>';

echo '</main>';

get_footer();
