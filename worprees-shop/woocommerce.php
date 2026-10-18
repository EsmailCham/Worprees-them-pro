<?php
/**
 * WooCommerce Template Overrides
 * Complete WooCommerce template overrides for shop, product, cart, checkout, my account
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Remove default WooCommerce wrappers
 */
remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

/**
 * Add custom WooCommerce wrappers
 */
add_action('woocommerce_before_main_content', 'worprees_woocommerce_wrapper_start', 10);
add_action('woocommerce_after_main_content', 'worprees_woocommerce_wrapper_end', 10);

function worprees_woocommerce_wrapper_start() {
    echo '<div class="worprees-woocommerce-wrapper">';
    echo '<div class="worprees-woocommerce-container">';
}

function worprees_woocommerce_wrapper_end() {
    echo '</div>';
    echo '</div>';
}

/**
 * Remove default WooCommerce sidebar
 */
remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);

/**
 * Add custom WooCommerce sidebar
 */
add_action('woocommerce_sidebar', 'worprees_woocommerce_sidebar', 10);

function worprees_woocommerce_sidebar() {
    if (is_active_sidebar('shop-sidebar')) {
        echo '<aside class="worprees-woocommerce-sidebar">';
        dynamic_sidebar('shop-sidebar');
        echo '</aside>';
    }
}

/**
 * Remove default WooCommerce breadcrumb
 */
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);

/**
 * Add custom WooCommerce breadcrumb
 */
add_action('woocommerce_before_main_content', 'worprees_woocommerce_breadcrumb', 20);

function worprees_woocommerce_breadcrumb() {
    if (function_exists('worprees_breadcrumb')) {
        worprees_breadcrumb();
    }
}

/**
 * Shop Page Template
 */
function worprees_woocommerce_shop_template() {
    global $wp_query;
    
    // Hero section
    echo '<section class="worprees-woocommerce-hero">';
    echo '<div class="worprees-woocommerce-hero-content">';
    echo '<h1 class="worprees-woocommerce-hero-title">' . esc_html__('فروشگاه', 'worprees') . '</h1>';
    
    // Shop description
    $shop_page_id = wc_get_page_id('shop');
    $shop_description = get_post_field('post_content', $shop_page_id);
    if ($shop_description) {
        echo '<div class="worprees-woocommerce-hero-description">' . wp_kses_post(wpautop($shop_description)) . '</div>';
    }
    echo '</div>';
    echo '</section>';
    
    // Shop toolbar
    echo '<div class="worprees-woocommerce-toolbar">';
    
    // View mode toggle
    echo '<div class="worprees-woocommerce-view-mode">';
    echo '<button class="worprees-woocommerce-view-mode-grid active" data-view="grid"><i class="fas fa-th"></i></button>';
    echo '<button class="worprees-woocommerce-view-mode-list" data-view="list"><i class="fas fa-list"></i></button>';
    echo '</div>';
    
    // Results count
    if (woocommerce_products_will_display()) {
        echo '<div class="worprees-woocommerce-results-count">';
        woocommerce_result_count();
        echo '</div>';
    }
    
    // Catalog ordering
    echo '<div class="worprees-woocommerce-catalog-ordering">';
    woocommerce_catalog_ordering();
    echo '</div>';
    
    echo '</div>';
    
    // Products grid/list
    echo '<div class="worprees-woocommerce-products">';
    
    if (woocommerce_product_loop()) {
        echo '<div class="worprees-woocommerce-products-grid">';
        
        while (have_posts()) {
            the_post();
            
            global $product;
            
            echo '<div class="worprees-woocommerce-product animate__animated animate__fadeInUp">';
            echo '<div class="worprees-woocommerce-product-thumbnail">';
            echo '<a href="' . esc_url(get_permalink()) . '">';
            echo get_the_post_thumbnail(get_the_ID(), 'worprees-product');
            
            // Product badges
            if ($product->is_on_sale()) {
                echo '<span class="worprees-woocommerce-product-badge worprees-woocommerce-product-badge-sale">' . esc_html__('فروش ویژه', 'worprees') . '</span>';
            }
            if ($product->is_featured()) {
                echo '<span class="worprees-woocommerce-product-badge worprees-woocommerce-product-badge-featured">' . esc_html__('ویژه', 'worprees') . '</span>';
            }
            if (!$product->is_in_stock()) {
                echo '<span class="worprees-woocommerce-product-badge worprees-woocommerce-product-badge-out-of-stock">' . esc_html__('ناموجود', 'worprees') . '</span>';
            }
            
            echo '</a>';
            
            // Product actions
            echo '<div class="worprees-woocommerce-product-actions">';
            echo '<button class="worprees-woocommerce-product-quick-view" onclick="worpreesQuickView(' . esc_js(get_the_ID()) . ')"><i class="fas fa-search"></i></button>';
            echo '<button class="worprees-woocommerce-product-wishlist" onclick="worpreesAddToWishlist(' . esc_js(get_the_ID()) . ')"><i class="far fa-heart"></i></button>';
            echo '<button class="worprees-woocommerce-product-compare" onclick="worpreesAddToCompare(' . esc_js(get_the_ID()) . ')"><i class="fas fa-exchange-alt"></i></button>';
            echo '</div>';
            echo '</div>';
            
            echo '<div class="worprees-woocommerce-product-info">';
            echo '<h3 class="worprees-woocommerce-product-title"><a href="' . esc_url(get_permalink()) . '">' . esc_html(get_the_title()) . '</a></h3>';
            echo '<div class="worprees-woocommerce-product-price">' . $product->get_price_html() . '</div>';
            
            // Rating
            if (function_exists('wc_get_rating_html')) {
                echo '<div class="worprees-woocommerce-product-rating">' . wc_get_rating_html($product->get_average_rating()) . '</div>';
            }
            
            echo '<div class="worprees-woocommerce-product-add-to-cart">';
            if ($product->is_purchasable() && $product->is_in_stock()) {
                echo '<button class="worprees-woocommerce-product-add-to-cart-button button" onclick="worpreesAddToCart(' . esc_js(get_the_ID()) . ')">' . esc_html__('افزودن به سبد خرید', 'worprees') . '</button>';
            } else {
                echo '<button class="worprees-woocommerce-product-add-to-cart-button button disabled">' . esc_html__('ناموجود', 'worprees') . '</button>';
            }
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
        
        echo '</div>';
        
        // Pagination
        echo '<div class="worprees-woocommerce-products-pagination">';
        woocommerce_pagination();
        echo '</div>';
        
    } else {
        echo '<div class="worprees-woocommerce-no-products">';
        echo '<p>' . esc_html__('هیچ محصولی یافت نشد.', 'worprees') . '</p>';
        echo '</div>';
    }
    
    echo '</div>';
}

/**
 * Single Product Template
 */
function worprees_woocommerce_single_product_template() {
    global $product;
    
    // Product image gallery
    echo '<div class="worprees-woocommerce-single-product">';
    echo '<div class="worprees-woocommerce-single-product-row">';
    
    echo '<div class="worprees-woocommerce-single-product-gallery">';
    
    // Main image
    echo '<div class="worprees-woocommerce-single-product-image">';
    echo '<div class="worprees-woocommerce-single-product-image-main">';
    echo get_the_post_thumbnail(get_the_ID(), 'worprees-large');
    
    // Product badges
    if ($product->is_on_sale()) {
        echo '<span class="worprees-woocommerce-single-product-badge worprees-woocommerce-single-product-badge-sale">' . esc_html__('فروش ویژه', 'worprees') . '</span>';
    }
    if ($product->is_featured()) {
        echo '<span class="worprees-woocommerce-single-product-badge worprees-woocommerce-single-product-badge-featured">' . esc_html__('ویژه', 'worprees') . '</span>';
    }
    if (!$product->is_in_stock()) {
        echo '<span class="worprees-woocommerce-single-product-badge worprees-woocommerce-single-product-badge-out-of-stock">' . esc_html__('ناموجود', 'worprees') . '</span>';
    }
    echo '</div>';
    
    // Thumbnails
    $gallery_ids = $product->get_gallery_image_ids();
    if ($gallery_ids) {
        echo '<div class="worprees-woocommerce-single-product-thumbnails">';
        foreach ($gallery_ids as $gallery_id) {
            $image = wp_get_attachment_image_src($gallery_id, 'worprees-thumbnail');
            if ($image) {
                echo '<div class="worprees-woocommerce-single-product-thumbnail">';
                echo '<img src="' . esc_url($image[0]) . '" alt="' . esc_attr(get_the_title($gallery_id)) . '">';
                echo '</div>';
            }
        }
        echo '</div>';
    }
    
    echo '</div>';
    echo '</div>';
    
    // Product info
    echo '<div class="worprees-woocommerce-single-product-info">';
    
    echo '<h1 class="worprees-woocommerce-single-product-title">' . esc_html(get_the_title()) . '</h1>';
    
    // Rating
    if (function_exists('wc_get_rating_html')) {
        echo '<div class="worprees-woocommerce-single-product-rating">' . wc_get_rating_html($product->get_average_rating()) . '</div>';
    }
    
    // Price
    echo '<div class="worprees-woocommerce-single-product-price">' . $product->get_price_html() . '</div>';
    
    // Short description
    if ($product->get_short_description()) {
        echo '<div class="worprees-woocommerce-single-product-short-description">' . wp_kses_post(wpautop($product->get_short_description())) . '</div>';
    }
    
    // Add to cart form
    echo '<div class="worprees-woocommerce-single-product-add-to-cart">';
    
    if ($product->is_type('variable')) {
        echo '<form class="worprees-woocommerce-single-product-variations-form variations_form cart" method="post" enctype="multipart/form-data" data-product_id="' . esc_attr($product->get_id()) . '" data-product_variations="' . esc_attr(json_encode($product->get_available_variations())) . '">';
        
        // Variation attributes
        $attributes = $product->get_variation_attributes();
        foreach ($attributes as $attribute_name => $options) {
            echo '<div class="worprees-woocommerce-single-product-variation">';
            echo '<label for="' . esc_attr($attribute_name) . '">' . esc_html(wc_attribute_label($attribute_name)) . '</label>';
            echo '<select name="' . esc_attr($attribute_name) . '" id="' . esc_attr($attribute_name) . '" class="worprees-woocommerce-single-product-variation-select">';
            echo '<option value="">' . esc_html__('انتخاب کنید', 'worprees') . '</option>';
            foreach ($options as $option) {
                echo '<option value="' . esc_attr($option) . '">' . esc_html($option) . '</option>';
            }
            echo '</select>';
            echo '</div>';
        }
        
        echo '<div class="worprees-woocommerce-single-product-quantity">';
        woocommerce_quantity_input(array(
            'min_value' => 1,
            'max_value' => $product->get_max_purchase_quantity(),
            'step' => $product->get_step(),
        ));
        echo '</div>';
        
        echo '<button type="submit" class="worprees-woocommerce-single-product-add-to-cart-button button alt">' . esc_html__('افزودن به سبد خرید', 'worprees') . '</button>';
        echo '</form>';
        
    } elseif ($product->is_type('simple')) {
        echo '<form class="worprees-woocommerce-single-product-simple-form cart" method="post" enctype="multipart/form-data">';
        
        echo '<div class="worprees-woocommerce-single-product-quantity">';
        woocommerce_quantity_input(array(
            'min_value' => 1,
            'max_value' => $product->get_max_purchase_quantity(),
            'step' => $product->get_step(),
        ));
        echo '</div>';
        
        echo '<button type="submit" class="worprees-woocommerce-single-product-add-to-cart-button button alt">' . esc_html__('افزودن به سبد خرید', 'worprees') . '</button>';
        echo '</form>';
        
    } else {
        echo '<button class="worprees-woocommerce-single-product-add-to-cart-button button alt disabled">' . esc_html__('ناموجود', 'worprees') . '</button>';
    }
    
    echo '</div>';
    
    // Wishlist and Compare
    echo '<div class="worprees-woocommerce-single-product-meta">';
    echo '<button class="worprees-woocommerce-single-product-wishlist" onclick="worpreesAddToWishlist(' . esc_js(get_the_ID()) . ')"><i class="far fa-heart"></i> ' . esc_html__('افزودن به علاقه مندی ها', 'worprees') . '</button>';
    echo '<button class="worprees-woocommerce-single-product-compare" onclick="worpreesAddToCompare(' . esc_js(get_the_ID()) . ')"><i class="fas fa-exchange-alt"></i> ' . esc_html__('مقایسه', 'worprees') . '</button>';
    echo '</div>';
    
    // SKU
    if ($product->get_sku()) {
        echo '<div class="worprees-woocommerce-single-product-sku">';
        echo '<span>' . esc_html__('کد محصول:', 'worprees') . '</span>';
        echo '<span>' . esc_html($product->get_sku()) . '</span>';
        echo '</div>';
    }
    
    // Categories
    if ($product->get_category_ids()) {
        echo '<div class="worprees-woocommerce-single-product-categories">';
        echo '<span>' . esc_html__('دسته بندی:', 'worprees') . '</span>';
        echo '<span>' . esc_html(get_the_term_list(get_the_ID(), 'product_cat', '', ', ')) . '</span>';
        echo '</div>';
    }
    
    // Tags
    if ($product->get_tag_ids()) {
        echo '<div class="worprees-woocommerce-single-product-tags">';
        echo '<span>' . esc_html__('برچسب ها:', 'worprees') . '</span>';
        echo '<span>' . esc_html(get_the_term_list(get_the_ID(), 'product_tag', '', ', ')) . '</span>';
        echo '</div>';
    }
    
    // Share
    if (function_exists('worprees_social_share')) {
        echo '<div class="worprees-woocommerce-single-product-share">';
        echo '<span>' . esc_html__('اشتراک گذاری:', 'worprees') . '</span>';
        echo worprees_social_share(get_the_ID());
        echo '</div>';
    }
    
    echo '</div>';
    echo '</div>';
    
    // Product description
    echo '<div class="worprees-woocommerce-single-product-description">';
    the_content();
    echo '</div>';
    
    // Related products
    echo '<div class="worprees-woocommerce-single-product-related">';
    echo '<h2>' . esc_html__('محصولات مرتبط', 'worprees') . '</h2>';
    woocommerce_related_products(array(
        'posts_per_page' => 4,
        'columns' => 4,
    ));
    echo '</div>';
    
    // Upsell products
    echo '<div class="worprees-woocommerce-single-product-upsell">';
    echo '<h2>' . esc_html__('شما ممکن است دوست داشته باشید', 'worprees') . '</h2>';
    woocommerce_upsell_display();
    echo '</div>';
    
    // Reviews
    echo '<div class="worprees-woocommerce-single-product-reviews">';
    comments_template();
    echo '</div>';
    
    echo '</div>';
}

/**
 * Cart Page Template
 */
function worprees_woocommerce_cart_template() {
    echo '<div class="worprees-woocommerce-cart">';
    echo '<h1>' . esc_html__('سبد خرید', 'worprees') . '</h1>';
    
    // Cart form
    echo '<form class="worprees-woocommerce-cart-form" action="' . esc_url(wc_get_cart_url()) . '" method="post">';
    
    // Cart table
    echo '<table class="worprees-woocommerce-cart-table shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellpadding="0" cellpadding="0">';
    echo '<thead>';
    echo '<tr>';
    echo '<th class="worprees-woocommerce-cart-product-remove">&nbsp;</th>';
    echo '<th class="worprees-woocommerce-cart-product-thumbnail">&nbsp;</th>';
    echo '<th class="worprees-woocommerce-cart-product-name">' . esc_html__('محصول', 'worprees') . '</th>';
    echo '<th class="worprees-woocommerce-cart-product-price">' . esc_html__('قیمت', 'worprees') . '</th>';
    echo '<th class="worprees-woocommerce-cart-product-quantity">' . esc_html__('تعداد', 'worprees') . '</th>';
    echo '<th class="worprees-woocommerce-cart-product-subtotal">' . esc_html__('جمع', 'worprees') . '</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';
    
    do_action('woocommerce_before_cart_contents');
    
    foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
        $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
        $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);
        
        if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) {
            $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
            
            echo '<tr class="worprees-woocommerce-cart-item woocommerce-cart-form__cart-item ' . esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)) . '">';
            
            // Remove
            echo '<td class="worprees-woocommerce-cart-product-remove">';
            echo apply_filters('woocommerce_cart_item_remove_link', sprintf(
                '<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-cart_item_key="%s"><i class="fas fa-times"></i></a>',
                esc_url(wc_get_cart_remove_url($cart_item_key)),
                esc_attr__('حذف', 'worprees'),
                esc_attr($product_id),
                esc_attr($cart_item_key)
            ), $cart_item_key);
            echo '</td>';
            
            // Thumbnail
            $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);
            if (!$product_permalink) {
                echo '<td class="worprees-woocommerce-cart-product-thumbnail">' . $thumbnail . '</td>';
            } else {
                echo '<td class="worprees-woocommerce-cart-product-thumbnail">';
                echo '<a href="' . esc_url($product_permalink) . '">' . $thumbnail . '</a>';
                echo '</td>';
            }
            
            // Name
            echo '<td class="worprees-woocommerce-cart-product-name" data-title="' . esc_attr__('محصول', 'worprees') . '">';
            if (!$product_permalink) {
                echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key) . '&nbsp;');
            } else {
                echo wp_kses_post(apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s">%s</a>', esc_url($product_permalink), $_product->get_name()), $cart_item, $cart_item_key));
            }
            
            // Meta
            do_action('woocommerce_after_cart_item_name', $cart_item, $cart_item_key);
            
            // Variation
            if ($_product->is_type('variation')) {
                echo '<div class="worprees-woocommerce-cart-product-variation">';
                echo '<span>' . esc_html__('گزينه:', 'worprees') . '</span>';
                echo '<span>' . wp_kses_post(wc_get_formatted_variation($_product, true)) . '</span>';
                echo '</div>';
            }
            
            echo '</td>';
            
            // Price
            echo '<td class="worprees-woocommerce-cart-product-price" data-title="' . esc_attr__('قیمت', 'worprees') . '">';
            echo apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key);
            echo '</td>';
            
            // Quantity
            echo '<td class="worprees-woocommerce-cart-product-quantity" data-title="' . esc_attr__('تعداد', 'worprees') . '">';
            if ($_product->is_sold_individually()) {
                $product_quantity = sprintf('1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key);
            } else {
                $product_quantity = woocommerce_quantity_input(array(
                    'input_name' => "cart[{$cart_item_key}][qty]",
                    'input_value' => $cart_item['quantity'],
                    'max_value' => $_product->get_max_purchase_quantity(),
                    'min_value' => '0',
                    'product_name' => $_product->get_name(),
                ), $_product, false);
            }
            echo apply_filters('woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item);
            echo '</td>';
            
            // Subtotal
            echo '<td class="worprees-woocommerce-cart-product-subtotal" data-title="' . esc_attr__('جمع', 'worprees') . '">';
            echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key);
            echo '</td>';
            
            echo '</tr>';
        }
    }
    
    do_action('woocommerce_after_cart_contents');
    do_action('woocommerce_after_cart_table');
    
    echo '</tbody>';
    echo '</table>';
    
    // Cart actions
    echo '<div class="worprees-woocommerce-cart-actions">';
    
    // Coupon
    if (WC()->cart->coupons_enabled()) {
        echo '<div class="worprees-woocommerce-cart-coupon">';
        echo '<h3>' . esc_html__('کد تخفیف', 'worprees') . '</h3>';
        echo '<p>' . esc_html__('اگر کد تخفیف دارید، اینجا وارد کنید.', 'worprees') . '</p>';
        echo '<div class="worprees-woocommerce-cart-coupon-form">';
        echo '<input type="text" name="coupon_code" class="worprees-woocommerce-cart-coupon-input" placeholder="' . esc_attr__('کد تخفیف', 'worprees') . '">';
        echo '<button type="submit" class="worprees-woocommerce-cart-coupon-button button" name="apply_coupon" value="' . esc_attr__('اعمال کد تخفیف', 'worprees') . '">' . esc_html__('اعمال کد تخفیف', 'worprees') . '</button>';
        echo '</div>';
        echo '</div>';
    }
    
    // Update cart
    echo '<div class="worprees-woocommerce-cart-update">';
    echo '<input type="submit" class="worprees-woocommerce-cart-update-button button" name="update_cart" value="' . esc_attr__('به روز رسانی سبد خرید', 'worprees') . '">';
    do_action('woocommerce_cart_actions');
    wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce');
    echo '</div>';
    
    echo '</div>';
    echo '</form>';
    
    // Cart collaterals
    echo '<div class="worprees-woocommerce-cart-collaterals">';
    
    // Cart totals
    echo '<div class="worprees-woocommerce-cart-totals">';
    echo '<h2>' . esc_html__('جمع کل سبد خرید', 'worprees') . '</h2>';
    echo '<table class="worprees-woocommerce-cart-totals-table shop_table shop_table_responsive">';
    echo '<tbody>';
    
    foreach (WC()->cart->get_totals() as $key => $total) {
        echo '<tr class="worprees-woocommerce-cart-totals-' . esc_attr($key) . '">';
        echo '<th>' . esc_html($total->label) . '</th>';
        echo '<td data-title="' . esc_attr($total->label) . '">' . $total->value . '</td>';
        echo '</tr>';
    }
    
    echo '</tbody>';
    echo '</table>';
    
    // Proceed to checkout
    echo '<div class="worprees-woocommerce-cart-checkout">';
    echo '<a href="' . esc_url(wc_get_checkout_url()) . '" class="worprees-woocommerce-cart-checkout-button checkout-button button alt wc-forward">' . esc_html__('تکمیل خرید', 'worprees') . '</a>';
    echo '</div>';
    
    echo '</div>';
    
    // Cross-sells
    if (WC()->cart->get_cross_sells()) {
        echo '<div class="worprees-woocommerce-cart-cross-sells">';
        echo '<h2>' . esc_html__('شما ممکن است دوست داشته باشید', 'worprees') . '</h2>';
        woocommerce_cross_sell_display();
        echo '</div>';
    }
    
    echo '</div>';
    echo '</div>';
}

/**
 * Checkout Page Template
 */
function worprees_woocommerce_checkout_template() {
    echo '<div class="worprees-woocommerce-checkout">';
    echo '<h1>' . esc_html__('تکمیل خرید', 'worprees') . '</h1>';
    
    // Checkout form
    echo '<form name="checkout" method="post" class="worprees-woocommerce-checkout-form checkout woocommerce-checkout" action="' . esc_url(wc_get_checkout_url()) . '" enctype="multipart/form-data">';
    
    // Billing
    echo '<div class="worprees-woocommerce-checkout-billing">';
    echo '<h2>' . esc_html__('آدرس صورتحساب', 'worprees') . '</h2>';
    do_action('woocommerce_checkout_billing');
    echo '</div>';
    
    // Shipping
    echo '<div class="worprees-woocommerce-checkout-shipping">';
    echo '<h2>' . esc_html__('آدرس ارسال', 'worprees') . '</h2>';
    do_action('woocommerce_checkout_shipping');
    echo '</div>';
    
    // Order review
    echo '<div class="worprees-woocommerce-checkout-review">';
    echo '<h2>' . esc_html__('بررسی سفارش', 'worprees') . '</h2>';
    do_action('woocommerce_checkout_order_review');
    echo '</div>';
    
    echo '</form>';
    
    // Checkout coupon form
    if (WC()->cart->coupons_enabled()) {
        echo '<div class="worprees-woocommerce-checkout-coupon">';
        echo '<h2>' . esc_html__('کد تخفیف دارید؟', 'worprees') . '</h2>';
        echo '<p>' . esc_html__('اگر کد تخفیف دارید، اینجا وارد کنید.', 'worprees') . '</p>';
        echo '<div class="worprees-woocommerce-checkout-coupon-form">';
        echo '<input type="text" name="coupon_code" class="worprees-woocommerce-checkout-coupon-input" placeholder="' . esc_attr__('کد تخفیف', 'worprees') . '">';
        echo '<button type="submit" class="worprees-woocommerce-checkout-coupon-button button" name="apply_coupon" value="' . esc_attr__('اعمال کد تخفیف', 'worprees') . '">' . esc_html__('اعمال کد تخفیف', 'worprees') . '</button>';
        echo '</div>';
        echo '</div>';
    }
    
    echo '</div>';
}

/**
 * My Account Page Template
 */
function worprees_woocommerce_my_account_template() {
    echo '<div class="worprees-woocommerce-my-account">';
    echo '<h1>' . esc_html__('حساب کاربری', 'worprees') . '</h1>';
    
    // My account navigation
    echo '<div class="worprees-woocommerce-my-account-navigation">';
    do_action('woocommerce_account_navigation');
    echo '</div>';
    
    // My account content
    echo '<div class="worprees-woocommerce-my-account-content">';
    do_action('woocommerce_account_content');
    echo '</div>';
    
    echo '</div>';
}

/**
 * Order Received Template
 */
function worprees_woocommerce_order_received_template($order_id) {
    echo '<div class="worprees-woocommerce-order-received">';
    echo '<h1>' . esc_html__('سفارش با موفقیت ثبت شد', 'worprees') . '</h1>';
    
    echo '<p>' . esc_html__('از خرید شما سپاسگزاریم. سفارش شما با موفقیت ثبت شد.', 'worprees') . '</p>';
    
    // Order details
    echo '<div class="worprees-woocommerce-order-received-details">';
    do_action('woocommerce_thankyou_' . WC()->payment_gateways->get_unfiltered_payment_method($_GET['payment_method']), $order_id);
    do_action('woocommerce_thankyou', $order_id);
    echo '</div>';
    
    echo '</div>';
}

/**
 * Hook into WooCommerce template loading
 */
add_filter('woocommerce_template_path', 'worprees_woocommerce_template_path', 10, 3);
function worprees_woocommerce_template_path($template, $template_name, $template_path) {
    // Use theme templates
    $theme_template_path = get_template_directory() . '/woocommerce/' . $template_name;
    
    if (file_exists($theme_template_path)) {
        return get_template_directory() . '/woocommerce/';
    }
    
    return $template_path;
}

/**
 * Add body classes for WooCommerce pages
 */
add_filter('body_class', 'worprees_woocommerce_body_classes');
function worprees_woocommerce_body_classes($classes) {
    if (worprees_is_woocommerce_page()) {
        $classes[] = 'worprees-woocommerce-page';
    }
    
    if (is_cart()) {
        $classes[] = 'worprees-woocommerce-cart-page';
    }
    
    if (is_checkout()) {
        $classes[] = 'worprees-woocommerce-checkout-page';
    }
    
    if (is_account_page()) {
        $classes[] = 'worprees-woocommerce-account-page';
    }
    
    if (is_product()) {
        $classes[] = 'worprees-woocommerce-product-page';
    }
    
    return $classes;
}

/**
 * Add custom classes to WooCommerce products
 */
add_filter('post_class', 'worprees_woocommerce_product_classes', 10, 3);
function worprees_woocommerce_product_classes($classes, $class, $post_id) {
    if (!worprees_is_woocommerce_active()) {
        return $classes;
    }
    
    if (get_post_type($post_id) === 'product') {
        $classes[] = 'worprees-woocommerce-product';
        
        $product = wc_get_product($post_id);
        if ($product) {
            if ($product->is_on_sale()) {
                $classes[] = 'worprees-woocommerce-product-on-sale';
            }
            if ($product->is_featured()) {
                $classes[] = 'worprees-woocommerce-product-featured';
            }
            if (!$product->is_in_stock()) {
                $classes[] = 'worprees-woocommerce-product-out-of-stock';
            }
        }
    }
    
    return $classes;
}

/**
 * Modify WooCommerce loop shop per page
 */
add_filter('loop_shop_per_page', 'worprees_woocommerce_loop_shop_per_page', 10);
function worprees_woocommerce_loop_shop_per_page($cols) {
    return 12;
}

/**
 * Modify WooCommerce loop shop columns
 */
add_filter('loop_shop_columns', 'worprees_woocommerce_loop_shop_columns', 10);
function worprees_woocommerce_loop_shop_columns($cols) {
    return 4;
}

/**
 * Modify WooCommerce related products columns
 */
add_filter('woocommerce_output_related_products_args', 'worprees_woocommerce_related_products_args', 10);
function worprees_woocommerce_related_products_args($args) {
    $args['posts_per_page'] = 4;
    $args['columns'] = 4;
    return $args;
}

/**
 * Modify WooCommerce upsell products columns
 */
add_filter('woocommerce_upsell_display_args', 'worprees_woocommerce_upsell_display_args', 10);
function worprees_woocommerce_upsell_display_args($args) {
    $args['posts_per_page'] = 4;
    $args['columns'] = 4;
    return $args;
}

/**
 * Modify WooCommerce cross sell products columns
 */
add_filter('woocommerce_cross_sells_columns', 'worprees_woocommerce_cross_sells_columns', 10);
function worprees_woocommerce_cross_sells_columns($cols) {
    return 4;
}

/**
 * Modify WooCommerce pagination args
 */
add_filter('woocommerce_pagination_args', 'worprees_woocommerce_pagination_args', 10);
function worprees_woocommerce_pagination_args($args) {
    $args['prev_text'] = esc_html__('قبلی', 'worprees');
    $args['next_text'] = esc_html__('بعدی', 'worprees');
    return $args;
}

/**
 * Modify WooCommerce breadcrumb defaults
 */
add_filter('woocommerce_breadcrumb_defaults', 'worprees_woocommerce_breadcrumb_defaults', 10);
function worprees_woocommerce_breadcrumb_defaults($defaults) {
    $defaults['delimiter'] = '<span class="worprees-woocommerce-breadcrumb-delimiter"><i class="fas fa-chevron-left"></i></span>';
    $defaults['wrap_before'] = '<nav class="worprees-woocommerce-breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">';
    $defaults['wrap_after'] = '</nav>';
    $defaults['before'] = '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">';
    $defaults['after'] = '</li>';
    $defaults['home'] = esc_html__('خانه', 'worprees');
    return $defaults;
}

/**
 * Add RTL support for WooCommerce
 */
add_filter('woocommerce_rtl_css', 'worprees_woocommerce_rtl_css', 10);
function worprees_woocommerce_rtl_css($rtl) {
    if (is_rtl()) {
        $rtl = true;
    }
    return $rtl;
}

/**
 * Add Persian localization for WooCommerce
 */
add_filter('woocommerce_countries_allowed_country_states', 'worprees_woocommerce_countries_allowed_country_states', 10);
function worprees_woocommerce_countries_allowed_country_states($states) {
    $states['IR'] = array(
        'TEH' => esc_html__('تهران', 'worprees'),
        'ISF' => esc_html__('اصفهان', 'worprees'),
        'SHI' => esc_html__('مشهد', 'worprees'),
        'KHO' => esc_html__('خوزستان', 'worprees'),
        'FAR' => esc_html__('فارس', 'worprees'),
        'KER' => esc_html__('کرمان', 'worprees'),
        'GIL' => esc_html__('گیلان', 'worprees'),
        'MAZ' => esc_html__('مازندران', 'worprees'),
        'KUR' => esc_html__('کردستان', 'worprees'),
        'LOR' => esc_html__('لرستان', 'worprees'),
    );
    return $states;
}

/**
 * Add Persian currency symbol
 */
add_filter('woocommerce_currency_symbol', 'worprees_woocommerce_currency_symbol', 10, 2);
function worprees_woocommerce_currency_symbol($currency_symbol, $currency) {
    if ($currency === 'IRR' || $currency === 'IRT') {
        $currency_symbol = 'تومان';
    }
    return $currency_symbol;
}

/**
 * Add Persian price format
 */
add_filter('woocommerce_price_format', 'worprees_woocommerce_price_format', 10, 2);
function worprees_woocommerce_price_format($format, $currency_pos) {
    if ($currency_pos === 'right') {
        $format = '%s%s';
    } elseif ($currency_pos === 'right_space') {
        $format = '%s&nbsp;%s';
    } elseif ($currency_pos === 'left') {
        $format = '%s%s';
    } elseif ($currency_pos === 'left_space') {
        $format = '%s&nbsp;%s';
    }
    return $format;
}

/**
 * Add Persian number formatting
 */
add_filter('woocommerce_price_thousand_sep', 'worprees_woocommerce_price_thousand_sep', 10);
function worprees_woocommerce_price_thousand_sep($thousand_sep) {
    return ',';
}

add_filter('woocommerce_price_decimal_sep', 'worprees_woocommerce_price_decimal_sep', 10);
function worprees_woocommerce_price_decimal_sep($decimal_sep) {
    return '/';
}

/**
 * Add Persian strings for WooCommerce
 */
add_filter('gettext', 'worprees_woocommerce_persian_strings', 10, 3);
function worprees_woocommerce_persian_strings($translated_text, $text, $domain) {
    if ($domain === 'woocommerce') {
        switch ($translated_text) {
            case 'Add to cart':
                $translated_text = 'افزودن به سبد خرید';
                break;
            case 'Add to Cart':
                $translated_text = 'افزودن به سبد خرید';
                break;
            case 'View cart':
                $translated_text = 'مشاهده سبد خرید';
                break;
            case 'Checkout':
                $translated_text = 'تکمیل خرید';
                break;
            case 'Place order':
                $translated_text = 'ثبت سفارش';
                break;
            case 'Proceed to checkout':
                $translated_text = 'تکمیل خرید';
                break;
            case 'Cart':
                $translated_text = 'سبد خرید';
                break;
            case 'Total':
                $translated_text = 'جمع';
                break;
            case 'Subtotal':
                $translated_text = 'جمع جزئی';
                break;
            case 'Quantity':
                $translated_text = 'تعداد';
                break;
            case 'Price':
                $translated_text = 'قیمت';
                break;
            case 'Product':
                $translated_text = 'محصول';
                break;
            case 'Remove':
                $translated_text = 'حذف';
                break;
            case 'Update cart':
                $translated_text = 'به روز رسانی سبد خرید';
                break;
            case 'Your order':
                $translated_text = 'سفارش شما';
                break;
            case 'Billing details':
                $translated_text = 'جزئیات صورتحساب';
                break;
            case 'Shipping details':
                $translated_text = 'جزئیات ارسال';
                break;
            case 'Payment method':
                $translated_text = 'روش پرداخت';
                break;
            case 'Order notes':
                $translated_text = 'یادداشت سفارش';
                break;
            case 'First Name':
                $translated_text = 'نام';
                break;
            case 'Last Name':
                $translated_text = 'نام خانوادگی';
                break;
            case 'Company Name':
                $translated_text = 'نام شرکت';
                break;
            case 'Country':
                $translated_text = 'کشور';
                break;
            case 'Address':
                $translated_text = 'آدرس';
                break;
            case 'City':
                $translated_text = 'شهر';
                break;
            case 'State':
                $translated_text = 'استان';
                break;
            case 'Postcode / ZIP':
                $translated_text = 'کد پستی';
                break;
            case 'Phone':
                $translated_text = 'تلفن';
                break;
            case 'Email Address':
                $translated_text = 'آدرس ایمیل';
                break;
            case 'Shipping method':
                $translated_text = 'روش ارسال';
                break;
            case 'Order total':
                $translated_text = 'جمع سفارش';
                break;
            case 'Payment methods':
                $translated_text = 'روش های پرداخت';
                break;
        }
    }
    return $translated_text;
}
