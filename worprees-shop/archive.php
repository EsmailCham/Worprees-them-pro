<?php
/**
 * The template for displaying archive pages
 * 
 * @package Worprees_Shop
 */

get_header();

// Breadcrumbs
if (function_exists('worprees_shop_breadcrumbs')) :
    worprees_shop_breadcrumbs();
endif;

echo '<main id="main" class="site-main">';

// Page Header
if (!is_paged()) :
    echo '<header class="archive-header py-5">';
    echo '<div class="container">';
    
    if (is_category()) :
        echo '<h1 class="archive-title">' . sprintf(esc_html__('دسته بندی: %s', 'worprees-shop'), single_cat_title('', false)) . '</h1>';
    elseif (is_tag()) :
        echo '<h1 class="archive-title">' . sprintf(esc_html__('برچسب: %s', 'worprees-shop'), single_tag_title('', false)) . '</h1>';
    elseif (is_author()) :
        echo '<h1 class="archive-title">' . sprintf(esc_html__('نویسنده: %s', 'worprees-shop'), get_the_author()) . '</h1>';
    elseif (is_date()) :
        if (is_day()) :
            echo '<h1 class="archive-title">' . sprintf(esc_html__('آرشیو: %s', 'worprees-shop'), get_the_date()) . '</h1>';
        elseif (is_month()) :
            echo '<h1 class="archive-title">' . sprintf(esc_html__('آرشیو: %s', 'worprees-shop'), get_the_date('F Y')) . '</h1>';
        elseif (is_year()) :
            echo '<h1 class="archive-title">' . sprintf(esc_html__('آرشیو: %s', 'worprees-shop'), get_the_date('Y')) . '</h1>';
        endif;
    elseif (is_post_type_archive()) :
        echo '<h1 class="archive-title">' . sprintf(esc_html__('آرشیو: %s', 'worprees-shop'), post_type_archive_title('', false)) . '</h1>';
    else :
        echo '<h1 class="archive-title">' . esc_html__('آرشیو', 'worprees-shop') . '</h1>';
    endif;
    
    echo '</div>';
    echo '</header>';
endif;

// Products Archive (for WooCommerce)
if (class_exists('WooCommerce') && (is_post_type_archive('product') || is_tax('product_cat') || is_tax('product_tag'))) :
    echo '<section class="products-archive-section py-5">';
    echo '<div class="container">';
    
    if (have_posts()) :
        echo '<div class="products-grid">';
        
        while (have_posts()) : the_post();
            global $product;
            
            echo '<article class="product-card">';
            
            // Product Image
            echo '<div class="product-image">';
            if (has_post_thumbnail()) :
                the_post_thumbnail('worprees-shop-product-thumbnail');
            else :
                echo '<img src="' . esc_url(wc_placeholder_img_src()) . '" alt="' . esc_attr__('تصویر محصول', 'worprees-shop') . '">';
            endif;
            
            // Product Badges
            if ($product->is_on_sale()) :
                echo '<span class="product-badge sale">' . esc_html__('فروش ویژه', 'worprees-shop') . '</span>';
            endif;
            
            echo '</div>';
            
            // Product Content
            echo '<div class="product-content">';
            echo '<h3 class="product-title"><a href="' . esc_url(get_permalink()) . '">' . esc_html(get_the_title()) . '</a></h3>';
            echo '<div class="product-price">' . $product->get_price_html() . '</div>';
            
            // Product actions
            echo '<div class="product-actions">';
            echo '<a href="' . esc_url(get_permalink()) . '" class="btn btn-outline">' . esc_html__('مشاهده محصول', 'worprees-shop') . '</a>';
            
            if ($product->is_type('simple') && $product->is_purchasable() && $product->is_in_stock()) :
                echo '<button class="btn add-to-cart-btn" data-product_id="' . esc_attr($product->get_id()) . '">' . esc_html__('افزودن به سبد', 'worprees-shop') . '</button>';
            endif;
            
            echo '</div>';
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
        echo '<div class="no-products text-center py-5">';
        echo '<p>' . esc_html__('هیچ محصولی یافت نشد', 'worprees-shop') . '</p>';
        echo '</div>';
    endif;
    
    echo '</div>';
    echo '</section>';
    
// Regular Archive (for posts)
else :
    echo '<section class="archive-section py-5">';
    echo '<div class="container">';
    
    if (have_posts()) :
        echo '<div class="posts-grid">';
        
        while (have_posts()) : the_post();
            echo '<article class="post-card">';
            
            // Featured Image
            if (has_post_thumbnail()) :
                echo '<div class="post-image">';
                echo '<a href="' . esc_url(get_permalink()) . '">';
                the_post_thumbnail('worprees-shop-product-thumbnail');
                echo '</a>';
                echo '</div>';
            endif;
            
            echo '<div class="post-content">';
            echo '<h3 class="post-title"><a href="' . esc_url(get_permalink()) . '">' . esc_html(get_the_title()) . '</a></h3>';
            echo '<div class="post-meta">';
            echo '<span><i class="fas fa-calendar-alt"></i> ' . esc_html(get_the_date()) . '</span>';
            echo '<span><i class="fas fa-user"></i> ' . esc_html(get_the_author()) . '</span>';
            echo '</div>';
            echo '<div class="post-excerpt">' . wp_kses_post(wpautop(get_the_excerpt())) . '</div>';
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
        echo '<div class="no-posts text-center py-5">';
        echo '<p>' . esc_html__('هیچ مطلبی یافت نشد', 'worprees-shop') . '</p>';
        echo '</div>';
    endif;
    
    echo '</div>';
    echo '</section>';
endif;

echo '</main>';

get_footer();
