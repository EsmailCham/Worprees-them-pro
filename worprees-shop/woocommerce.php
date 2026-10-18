<?php
/**
 * Worprees Shop WooCommerce Template Overrides
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
 * WooCommerce shop page template
 */
function worprees_woocommerce_shop_template() {
    global $wp_query;
    
    // Shop toolbar
    echo '<div class="worprees-woocommerce-toolbar">';
    
    // View mode toggle
    echo '<div class="worprees-woocommerce-view-mode">';
    echo '<button class="view-mode-button grid-view active" data-view="grid"><i class="fas fa-th"></i></button>';
    echo '<button class="view-mode-button list-view" data-view="list"><i class="fas fa-list"></i></button>';
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
    
    // Products loop
    if (woocommerce_product_loop()) {
        echo '<div class="worprees-woocommerce-products">';
        
        while (have_posts()) {
            the_post();
            
            global $product;
            
            echo '<div class="product-card animate-fadeInUp">';
            echo '<div class="product-thumbnail">';
            
            if (has_post_thumbnail()) {
                the_post_thumbnail('worprees-product');
            } else {
                echo '<img src="' . esc_url(wc_placeholder_img_src()) . '" alt="' . esc_attr(get_the_title()) . '">';
            }
            
            // Product badges
            if ($product->is_on_sale()) {
                echo '<span class="product-badge product-badge-sale">فروش ویژه</span>';
            }
            if ($product->is_featured()) {
                echo '<span class="product-badge product-badge-featured">ویژه</span>';
            }
            if (!$product->is_in_stock()) {
                echo '<span class="product-badge product-badge-out-of-stock">ناموجود</span>';
            }
            
            echo '</div>';
            
            echo '<div class="product-info">';
            echo '<h3 class="product-title"><a href="' . esc_url(get_permalink()) . '">' . esc_html(get_the_title()) . '</a></h3>';
            echo '<div class="product-price">' . $product->get_price_html() . '</div>';
            
            // Rating
            if (function_exists('wc_get_rating_html')) {
                echo '<div class="product-rating">' . wc_get_rating_html($product->get_average_rating()) . '</div>';
            }
            
            echo '<div class="product-actions">';
            if ($product->is_purchasable() && $product->is_in_stock()) {
                echo '<button class="add-to-cart-button btn btn-primary" data-product-id="' . esc_attr(get_the_ID()) . '">';
                echo '<i class="fas fa-shopping-cart"></i>';
                echo '</button>';
            } else {
                echo '<button class="add-to-cart-button btn btn-primary disabled">ناموجود</button>';
            }
            echo '<button class="quick-view-button" data-product-id="' . esc_attr(get_the_ID()) . '"><i class="fas fa-search"></i></button>';
            echo '<button class="add-to-wishlist" data-product-id="' . esc_attr(get_the_ID()) . '"><i class="far fa-heart"></i></button>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
        
        echo '</div>';
        
        // Pagination
        echo '<div class="worprees-woocommerce-pagination">';
        woocommerce_pagination();
        echo '</div>';
        
    } else {
        echo '<div class="worprees-woocommerce-no-products">';
        echo '<p>' . esc_html__('هیچ محصولی یافت نشد.', 'worprees') . '</p>';
        echo '</div>';
    }
}

/**
 * WooCommerce single product template
 */
function worprees_woocommerce_single_product_template() {
    global $product;
    
    // Product image gallery
    echo '<div class="worprees-woocommerce-single-product">';
    echo '<div class="worprees-woocommerce-single-product-row">';
    
    echo '<div class="worprees-woocommerce-single-product-gallery">';
    
    // Main image
    echo '<div class="worprees-woocommerce-single-product-image">';
    if (has_post_thumbnail()) {
        echo get_the_post_thumbnail(get_the_ID(), 'worprees-large');
    } else {
        echo '<img src="' . esc_url(wc_placeholder_img_src()) . '" alt="' . esc_attr(get_the_title()) . '">';
    }
    
    // Product badges
    if ($product->is_on_sale()) {
        echo '<span class="product-badge product-badge-sale">فروش ویژه</span>';
    }
    if ($product->is_featured()) {
        echo '<span class="product-badge product-badge-featured">ویژه</span>';
    }
    if (!$product->is_in_stock()) {
        echo '<span class="product-badge product-badge-out-of-stock">ناموجود</span>';
    }
    echo '</div>';
    
    // Thumbnails
    $gallery_ids = $product->get_gallery_image_ids();
    if ($gallery_ids) {
        echo '<div class="worprees-woocommerce-single-product-thumbnails">';
        foreach ($gallery_ids as $gallery_id) {
            $image = wp_get_attachment_image_src($gallery_id, 'worprees-thumbnail');
            if ($image) {
                echo '<div class="product-thumbnail-item">';
                echo '<img src="' . esc_url($image[0]) . '" alt="' . esc_attr(get_the_title($gallery_id)) . '">';
                echo '</div>';
            }
        }
        echo '</div>';
    }
    
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
        echo '<form class="worprees-woocommerce-single-product-variations-form variations_form cart" method="post" enctype="multipart/form-data" data-product_id="' . esc_attr($product->get_id()) . '">';
        
        // Variation attributes
        $attributes = $product->get_variation_attributes();
        foreach ($attributes as $attribute_name => $options) {
            echo '<div class="worprees-woocommerce-single-product-variation">';
            echo '<label for="' . esc_attr($attribute_name) . '">' . esc_html(wc_attribute_label($attribute_name)) . '</label>';
            echo '<select name="' . esc_attr($attribute_name) . '" id="' . esc_attr($attribute_name) . '" class="variation-select">';
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
        ));
        echo '</div>';
        
        echo '<button type="submit" class="worprees-woocommerce-single-product-add-to-cart-button btn btn-primary">';
        echo '<i class="fas fa-shopping-cart"></i> افزودن به سبد خرید';
        echo '</button>';
        echo '</form>';
        
    } elseif ($product->is_type('simple')) {
        echo '<form class="worprees-woocommerce-single-product-simple-form cart" method="post" enctype="multipart/form-data">';
        
        echo '<div class="worprees-woocommerce-single-product-quantity">';
        woocommerce_quantity_input(array(
            'min_value' => 1,
            'max_value' => $product->get_max_purchase_quantity(),
        ));
        echo '</div>';
        
        echo '<button type="submit" class="worprees-woocommerce-single-product-add-to-cart-button btn btn-primary">';
        echo '<i class="fas fa-shopping-cart"></i> افزودن به سبد خرید';
        echo '</button>';
        echo '</form>';
        
    } else {
        echo '<button class="worprees-woocommerce-single-product-add-to-cart-button btn btn-primary disabled">ناموجود</button>';
    }
    
    echo '</div>';
    
    // Wishlist and Compare
    echo '<div class="worprees-woocommerce-single-product-meta">';
    echo '<button class="worprees-woocommerce-single-product-wishlist add-to-wishlist" data-product-id="' . esc_attr(get_the_ID()) . '">';
    echo '<i class="far fa-heart"></i> افزودن به علاقه‌مندها';
    echo '</button>';
    echo '<button class="worprees-woocommerce-single-product-compare add-to-compare" data-product-id="' . esc_attr(get_the_ID()) . '">';
    echo '<i class="fas fa-exchange-alt"></i> مقایسه';
    echo '</button>';
    echo '</div>';
    
    // SKU
    if ($product->get_sku()) {
        echo '<div class="worprees-woocommerce-single-product-sku">';
        echo '<span>کد محصول:</span>';
        echo '<span>' . esc_html($product->get_sku()) . '</span>';
        echo '</div>';
    }
    
    // Categories
    if ($product->get_category_ids()) {
        echo '<div class="worprees-woocommerce-single-product-categories">';
        echo '<span>دسته‌بندی:</span>';
        echo '<span>' . esc_html(get_the_term_list(get_the_ID(), 'product_cat', '', ', ')) . '</span>';
        echo '</div>';
    }
    
    // Tags
    if ($product->get_tag_ids()) {
        echo '<div class="worprees-woocommerce-single-product-tags">';
        echo '<span>برچسب‌ها:</span>';
        echo '<span>' . esc_html(get_the_term_list(get_the_ID(), 'product_tag', '', ', ')) . '</span>';
        echo '</div>';
    }
    
    // Share
    if (function_exists('worprees_social_share')) {
        echo '<div class="worprees-woocommerce-single-product-share">';
        echo '<span>اشتراک‌گذاری:</span>';
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
    echo '<h2>محصولات مرتبط</h2>';
    woocommerce_related_products(array(
        'posts_per_page' => 4,
        'columns' => 4,
    ));
    echo '</div>';
    
    // Upsell products
    echo '<div class="worprees-woocommerce-single-product-upsell">';
    echo '<h2>شما ممکن است دوست داشته باشید</h2>';
    woocommerce_upsell_display();
    echo '</div>';
    
    // Reviews
    echo '<div class="worprees-woocommerce-single-product-reviews">';
    comments_template();
    echo '</div>';
}

/**
 * WooCommerce cart page template
 */
function worprees_woocommerce_cart_template() {
    echo '<div class="worprees-woocommerce-cart">';
    echo '<h1>سبد خرید</h1>';
    
    // Cart form
    echo '<form class="worprees-woocommerce-cart-form" action="' . esc_url(wc_get_cart_url()) . '" method="post">';
    
    // Cart table
    echo '<table class="worprees-woocommerce-cart-table shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellpadding="0" cellpadding="0">';
    echo '<thead>';
    echo '<tr>';
    echo '<th class="product-remove">&nbsp;</th>';
    echo '<th class="product-thumbnail">&nbsp;</th>';
    echo '<th class="product-name">محصول</th>';
    echo '<th class="product-price">قیمت</th>';
    echo '<th class="product-quantity">تعداد</th>';
    echo '<th class="product-subtotal">جمع</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';
    
    do_action('woocommerce_before_cart_contents');
    
    foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
        $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
        $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);
        
        if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) {
            $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
            
            echo '<tr class="woocommerce-cart-form__cart-item ' . esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)) . '">';
            
            // Remove
            echo '<td class="product-remove">';
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
                echo '<td class="product-thumbnail">' . $thumbnail . '</td>';
            } else {
                echo '<td class="product-thumbnail">';
                echo '<a href="' . esc_url($product_permalink) . '">' . $thumbnail . '</a>';
                echo '</td>';
            }
            
            // Name
            echo '<td class="product-name" data-title="محصول">';
            if (!$product_permalink) {
                echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key) . '&nbsp;');
            } else {
                echo wp_kses_post(apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s">%s</a>', esc_url($product_permalink), $_product->get_name()), $cart_item, $cart_item_key));
            }
            
            // Meta
            do_action('woocommerce_after_cart_item_name', $cart_item, $cart_item_key);
            
            // Variation
            if ($_product->is_type('variation')) {
                echo '<div class="product-variation">';
                echo '<span>گزينه:</span>';
                echo '<span>' . wp_kses_post(wc_get_formatted_variation($_product, true)) . '</span>';
                echo '</div>';
            }
            
            echo '</td>';
            
            // Price
            echo '<td class="product-price" data-title="قیمت">';
            echo apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key);
            echo '</td>';
            
            // Quantity
            echo '<td class="product-quantity" data-title="تعداد">';
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
            echo '<td class="product-subtotal" data-title="جمع">';
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
        echo '<h3>کد تخفیف</h3>';
        echo '<p>اگر کد تخفیف دارید، اینجا وارد کنید.</p>';
        echo '<div class="worprees-woocommerce-cart-coupon-form">';
        echo '<input type="text" name="coupon_code" class="worprees-woocommerce-cart-coupon-input" placeholder="کد تخفیف" />';
        echo '<button type="submit" class="worprees-woocommerce-cart-coupon-button btn btn-outline" name="apply_coupon" value="اعمال کد تخفیف">اعمال کد تخفیف</button>';
        echo '</div>';
        echo '</div>';
    }
    
    // Update cart
    echo '<div class="worprees-woocommerce-cart-update">';
    echo '<input type="submit" class="worprees-woocommerce-cart-update-button btn btn-outline" name="update_cart" value="به روز رسانی سبد خرید" />';
    do_action('woocommerce_cart_actions');
    wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce');
    echo '</div>';
    
    echo '</div>';
    echo '</form>';
    
    // Cart collaterals
    echo '<div class="worprees-woocommerce-cart-collaterals">';
    
    // Cart totals
    echo '<div class="worprees-woocommerce-cart-totals">';
    echo '<h2>جمع کل سبد خرید</h2>';
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
    echo '<a href="' . esc_url(wc_get_checkout_url()) . '" class="worprees-woocommerce-cart-checkout-button checkout-button btn btn-primary wc-forward">تکمیل خرید</a>';
    echo '</div>';
    
    echo '</div>';
    
    // Cross-sells
    if (WC()->cart->get_cross_sells()) {
        echo '<div class="worprees-woocommerce-cart-cross-sells">';
        echo '<h2>شما ممکن است دوست داشته باشید</h2>';
        woocommerce_cross_sell_display();
        echo '</div>';
    }
    
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
 * Add RTL support for WooCommerce
 */
add_filter('woocommerce_rtl_css', 'worprees_woocommerce_rtl_css', 10);
function worprees_woocommerce_rtl_css($rtl) {
    if (is_rtl()) {
        $rtl = true;
    }
    return $rtl;
}
