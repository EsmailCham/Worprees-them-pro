<?php
/**
 * The template for displaying all single posts
 * 
 * @package Worprees_Shop
 */

get_header();

// Breadcrumbs
if (function_exists('worprees_shop_breadcrumbs')) :
    worprees_shop_breadcrumbs();
endif;

echo '<main id="main" class="site-main">';

while (have_posts()) : the_post();
    echo '<article id="post-' . esc_attr(get_the_ID()) . '" ' . post_class('single-post py-5');
    echo '>';
    
    // Featured Image
    if (has_post_thumbnail()) :
        echo '<div class="single-post-thumbnail">';
        the_post_thumbnail('worprees-shop-banner', array(
            'class' => 'single-post-image',
            'alt' => get_the_title(),
        ));
        echo '</div>';
    endif;
    
    echo '<div class="container">';
    echo '<div class="row">';
    
    // Main Content
    echo '<div class="col-lg-8 col-md-12">';
    echo '<div class="single-post-content">';
    
    // Title
    echo '<header class="single-post-header">';
    the_title('<h1 class="single-post-title">', '</h1>');
    echo '</header>';
    
    // Post Meta
    echo '<div class="single-post-meta">';
    
    // Author
    echo '<span class="post-author"><i class="fas fa-user"></i> ' . esc_html(get_the_author()) . '</span>';
    
    // Date
    echo '<span class="post-date"><i class="fas fa-calendar-alt"></i> ' . esc_html(get_the_date()) . '</span>';
    
    // Reading Time
    $reading_time = worprees_shop_calculate_reading_time(get_the_content());
    if ($reading_time) :
        echo '<span class="post-reading-time"><i class="fas fa-clock"></i> ' . esc_html($reading_time) . '</span>';
    endif;
    
    // Comments Count
    if (comments_open() || get_comments_number()) :
        echo '<span class="post-comments"><i class="fas fa-comments"></i> ';
        comments_popup_link(
            esc_html__('بدون نظر', 'worprees-shop'),
            esc_html__('1 نظر', 'worprees-shop'),
            esc_html__('% نظر', 'worprees-shop')
        );
        echo '</span>';
    endif;
    
    echo '</div>';
    
    // Post Content
    echo '<div class="single-post-body">';
    the_content();
    echo '</div>';
    
    // Tags
    $tags = get_the_tags();
    if (!empty($tags)) :
        echo '<div class="single-post-tags">';
        echo '<span>' . esc_html__('برچسب ها:', 'worprees-shop') . '</span>';
        foreach ($tags as $tag) :
            echo '<a href="' . esc_url(get_tag_link($tag->term_id)) . '" class="badge badge-secondary">' . esc_html($tag->name) . '</a>';
        endforeach;
        echo '</div>';
    endif;
    
    // Comments
    if (comments_open() || get_comments_number()) :
        echo '<div class="comments-section">';
        comments_template();
        echo '</div>';
    endif;
    
    echo '</div>';
    echo '</div>';
    
    // Sidebar
    echo '<aside class="col-lg-4 col-md-12">';
    if (is_active_sidebar('sidebar-1')) :
        dynamic_sidebar('sidebar-1');
    else :
        // Fallback sidebar content
        echo '<div class="sidebar-widget">';
        echo '<h3 class="widget-title">' . esc_html__('جستجو', 'worprees-shop') . '</h3>';
        get_search_form();
        echo '</div>';
        
        echo '<div class="sidebar-widget">';
        echo '<h3 class="widget-title">' . esc_html__('آخرین مقالات', 'worprees-shop') . '</h3>';
        echo '<ul class="recent-posts">';
        $recent_posts = wp_get_recent_posts(array(
            'numberposts' => 5,
            'post_status' => 'publish'
        ));
        foreach ($recent_posts as $recent) :
            echo '<li><a href="' . esc_url(get_permalink($recent["ID"])) . '">' . esc_html($recent["post_title"]) . '</a></li>';
        endforeach;
        echo '</ul>';
        echo '</div>';
        
        echo '<div class="sidebar-widget">';
        echo '<h3 class="widget-title">' . esc_html__('دسته بندی ها', 'worprees-shop') . '</h3>';
        echo '<ul class="categories-list">';
        $categories = get_categories(array(
            'orderby' => 'name',
            'order' => 'ASC'
        ));
        foreach ($categories as $category) :
            echo '<li><a href="' . esc_url(get_category_link($category->term_id)) . '">' . esc_html($category->name) . '</a></li>';
        endforeach;
        echo '</ul>';
        echo '</div>';
    endif;
    echo '</aside>';
    
    echo '</div>';
    echo '</div>';
    
    echo '</article>';
endwhile;

echo '</main>';

get_footer();
