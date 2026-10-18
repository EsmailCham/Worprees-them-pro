<?php
/**
 * The main template file
 * 
 * @package Worprees_Shop
 */

get_header();

// Preloader
echo '<div class="preloader" id="worpreesPreloader">';
echo '<div class="preloader-content">';
echo '<div class="loader"></div>';
echo '<h2>' . esc_html__('در حال بارگذاری...', 'worprees-shop') . '</h2>';
echo '</div>';
echo '</div>';

// Hero Section
echo '<section class="hero">';
echo '<div class="container">';
echo '<div class="hero-content">';
echo '<h1>' . esc_html__('به فروشگاه آنلاین ما خوش آمدید', 'worprees-shop') . '</h1>';
echo '<p>' . esc_html__('مرجع خرید محصولات با کیفیت و قیمت مناسب', 'worprees-shop') . '</p>';
echo '<a href="' . esc_url(wc_get_page_permalink('shop')) . '" class="btn btn-primary btn-lg">' . esc_html__('مشاهده محصولات', 'worprees-shop') . '</a>';
echo '</div>';
echo '</div>';
echo '</section>';

echo '<main id="main" class="site-main">';

// Featured Products Section
if (class_exists('WooCommerce')) :
    $featured_products = new WP_Query(array(
        'post_type' => 'product',
        'posts_per_page' => 8,
        'meta_query' => array(
            array(
                'key' => '_featured',
                'value' => 'yes',
                'compare' => '='
            )
        )
    ));
    
    if ($featured_products->have_posts()) :
        echo '<section class="products-section py-5">';
        echo '<div class="container">';
        echo '<div class="section-title">';
        echo '<h2>' . esc_html__('محصولات ویژه', 'worprees-shop') . '</h2>';
        echo '<p>' . esc_html__('محصولات ویژه و پرفروش ما را کشف کنید', 'worprees-shop') . '</p>';
        echo '</div>';
        
        echo '<div class="products-grid">';
        while ($featured_products->have_posts()) : $featured_products->the_post();
            global $product;
            
            echo '<article class="product-card">';
            
            // Product image
            echo '<div class="product-image">';
            if (has_post_thumbnail()) :
                the_post_thumbnail('worprees-shop-product-thumbnail');
            else :
                echo '<img src="' . esc_url(wc_placeholder_img_src()) . '" alt="' . esc_attr__('تصویر محصول', 'worprees-shop') . '">';
            endif;
            
            // Product badge
            if ($product->is_on_sale()) :
                echo '<span class="product-badge sale">' . esc_html__('فروش ویژه', 'worprees-shop') . '</span>';
            endif;
            
            echo '</div>';
            
            // Product content
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
        wp_reset_postdata();
        echo '</div>';
        echo '</div>';
        echo '</section>';
    endif;
endif;

// Latest Products Section
if (class_exists('WooCommerce')) :
    $latest_products = new WP_Query(array(
        'post_type' => 'product',
        'posts_per_page' => 6,
        'orderby' => 'date',
        'order' => 'DESC'
    ));
    
    if ($latest_products->have_posts()) :
        echo '<section class="products-section py-5 bg-light">';
        echo '<div class="container">';
        echo '<div class="section-title">';
        echo '<h2>' . esc_html__('جدیدترین محصولات', 'worprees-shop') . '</h2>';
        echo '<p>' . esc_html__('آخرین محصولات اضافه شده به فروشگاه', 'worprees-shop') . '</p>';
        echo '</div>';
        
        echo '<div class="products-grid">';
        while ($latest_products->have_posts()) : $latest_products->the_post();
            global $product;
            
            echo '<article class="product-card">';
            
            // Product image
            echo '<div class="product-image">';
            if (has_post_thumbnail()) :
                the_post_thumbnail('worprees-shop-product-thumbnail');
            else :
                echo '<img src="' . esc_url(wc_placeholder_img_src()) . '" alt="' . esc_attr__('تصویر محصول', 'worprees-shop') . '">';
            endif;
            
            // Product badge
            if ($product->is_on_sale()) :
                echo '<span class="product-badge sale">' . esc_html__('فروش ویژه', 'worprees-shop') . '</span>';
            endif;
            
            echo '</div>';
            
            // Product content
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
        wp_reset_postdata();
        echo '</div>';
        
        // View all products button
        echo '<div class="text-center mt-5">';
        echo '<a href="' . esc_url(wc_get_page_permalink('shop')) . '" class="btn btn-primary btn-lg">' . esc_html__('مشاهده همه محصولات', 'worprees-shop') . '</a>';
        echo '</div>';
        
        echo '</div>';
        echo '</section>';
    endif;
endif;

echo '</main>';

get_footer();
