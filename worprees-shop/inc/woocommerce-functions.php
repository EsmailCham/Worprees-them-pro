<?php
/**
 * Worprees Shop WooCommerce Functions
 * Helper functions for WooCommerce integration
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Check if WooCommerce is active
 */
if (!function_exists('worprees_woocommerce_is_active')) {
    function worprees_woocommerce_is_active() {
        return class_exists('WooCommerce');
    }
}

/**
 * Get WooCommerce product
 */
if (!function_exists('worprees_woocommerce_get_product')) {
    function worprees_woocommerce_get_product($product_id) {
        if (!worprees_woocommerce_is_active()) {
            return null;
        }
        return wc_get_product($product_id);
    }
}

/**
 * Get WooCommerce product price
 */
if (!function_exists('worprees_woocommerce_get_product_price')) {
    function worprees_woocommerce_get_product_price($product_id) {
        $product = worprees_woocommerce_get_product($product_id);
        if (!$product) {
            return '';
        }
        return $product->get_price_html();
    }
}

/**
 * Get WooCommerce product regular price
 */
if (!function_exists('worprees_woocommerce_get_product_regular_price')) {
    function worprees_woocommerce_get_product_regular_price($product_id) {
        $product = worprees_woocommerce_get_product($product_id);
        if (!$product) {
            return '';
        }
        return $product->get_regular_price();
    }
}

/**
 * Get WooCommerce product sale price
 */
if (!function_exists('worprees_woocommerce_get_product_sale_price')) {
    function worprees_woocommerce_get_product_sale_price($product_id) {
        $product = worprees_woocommerce_get_product($product_id);
        if (!$product) {
            return '';
        }
        return $product->get_sale_price();
    }
}

/**
 * Check if WooCommerce product is on sale
 */
if (!function_exists('worprees_woocommerce_is_product_on_sale')) {
    function worprees_woocommerce_is_product_on_sale($product_id) {
        $product = worprees_woocommerce_get_product($product_id);
        if (!$product) {
            return false;
        }
        return $product->is_on_sale();
    }
}

/**
 * Check if WooCommerce product is featured
 */
if (!function_exists('worprees_woocommerce_is_product_featured')) {
    function worprees_woocommerce_is_product_featured($product_id) {
        $product = worprees_woocommerce_get_product($product_id);
        if (!$product) {
            return false;
        }
        return $product->is_featured();
    }
}

/**
 * Check if WooCommerce product is in stock
 */
if (!function_exists('worprees_woocommerce_is_product_in_stock')) {
    function worprees_woocommerce_is_product_in_stock($product_id) {
        $product = worprees_woocommerce_get_product($product_id);
        if (!$product) {
            return false;
        }
        return $product->is_in_stock();
    }
}

/**
 * Check if WooCommerce product is purchasable
 */
if (!function_exists('worprees_woocommerce_is_product_purchasable')) {
    function worprees_woocommerce_is_product_purchasable($product_id) {
        $product = worprees_woocommerce_get_product($product_id);
        if (!$product) {
            return false;
        }
        return $product->is_purchasable();
    }
}

/**
 * Get WooCommerce product average rating
 */
if (!function_exists('worprees_woocommerce_get_product_rating')) {
    function worprees_woocommerce_get_product_rating($product_id) {
        $product = worprees_woocommerce_get_product($product_id);
        if (!$product) {
            return 0;
        }
        return $product->get_average_rating();
    }
}

/**
 * Get WooCommerce product review count
 */
if (!function_exists('worprees_woocommerce_get_product_review_count')) {
    function worprees_woocommerce_get_product_review_count($product_id) {
        $product = worprees_woocommerce_get_product($product_id);
        if (!$product) {
            return 0;
        }
        return $product->get_review_count();
    }
}

/**
 * Get WooCommerce product stock status
 */
if (!function_exists('worprees_woocommerce_get_product_stock_status')) {
    function worprees_woocommerce_get_product_stock_status($product_id) {
        $product = worprees_woocommerce_get_product($product_id);
        if (!$product) {
            return '';
        }
        return $product->get_stock_status();
    }
}

/**
 * Get WooCommerce product stock quantity
 */
if (!function_exists('worprees_woocommerce_get_product_stock_quantity')) {
    function worprees_woocommerce_get_product_stock_quantity($product_id) {
        $product = worprees_woocommerce_get_product($product_id);
        if (!$product) {
            return 0;
        }
        return $product->get_stock_quantity();
    }
}

/**
 * Get WooCommerce product categories
 */
if (!function_exists('worprees_woocommerce_get_product_categories')) {
    function worprees_woocommerce_get_product_categories($product_id) {
        $product = worprees_woocommerce_get_product($product_id);
        if (!$product) {
            return array();
        }
        return $product->get_category_ids();
    }
}

/**
 * Get WooCommerce product tags
 */
if (!function_exists('worprees_woocommerce_get_product_tags')) {
    function worprees_woocommerce_get_product_tags($product_id) {
        $product = worprees_woocommerce_get_product($product_id);
        if (!$product) {
            return array();
        }
        return $product->get_tag_ids();
    }
}

/**
 * Get WooCommerce product image
 */
if (!function_exists('worprees_woocommerce_get_product_image')) {
    function worprees_woocommerce_get_product_image($product_id, $size = 'shop_thumbnail') {
        $product = worprees_woocommerce_get_product($product_id);
        if (!$product) {
            return '';
        }
        return $product->get_image($size);
    }
}

/**
 * Get WooCommerce product image URL
 */
if (!function_exists('worprees_woocommerce_get_product_image_url')) {
    function worprees_woocommerce_get_product_image_url($product_id, $size = 'shop_thumbnail') {
        $product = worprees_woocommerce_get_product($product_id);
        if (!$product) {
            return '';
        }
        $image_id = $product->get_image_id();
        if ($image_id) {
            $image = wp_get_attachment_image_src($image_id, $size);
            return $image ? $image[0] : '';
        }
        return '';
    }
}

/**
 * Get WooCommerce product gallery images
 */
if (!function_exists('worprees_woocommerce_get_product_gallery')) {
    function worprees_woocommerce_get_product_gallery($product_id) {
        $product = worprees_woocommerce_get_product($product_id);
        if (!$product) {
            return array();
        }
        return $product->get_gallery_image_ids();
    }
}

/**
 * Get WooCommerce product short description
 */
if (!function_exists('worprees_woocommerce_get_product_short_description')) {
    function worprees_woocommerce_get_product_short_description($product_id) {
        $product = worprees_woocommerce_get_product($product_id);
        if (!$product) {
            return '';
        }
        return $product->get_short_description();
    }
}

/**
 * Get WooCommerce product description
 */
if (!function_exists('worprees_woocommerce_get_product_description')) {
    function worprees_woocommerce_get_product_description($product_id) {
        $product = worprees_woocommerce_get_product($product_id);
        if (!$product) {
            return '';
        }
        return $product->get_description();
    }
}

/**
 * Get WooCommerce product SKU
 */
if (!function_exists('worprees_woocommerce_get_product_sku')) {
    function worprees_woocommerce_get_product_sku($product_id) {
        $product = worprees_woocommerce_get_product($product_id);
        if (!$product) {
            return '';
        }
        return $product->get_sku();
    }
}

/**
 * Get WooCommerce cart count
 */
if (!function_exists('worprees_woocommerce_get_cart_count')) {
    function worprees_woocommerce_get_cart_count() {
        if (!worprees_woocommerce_is_active()) {
            return 0;
        }
        return WC()->cart->get_cart_contents_count();
    }
}

/**
 * Get WooCommerce cart total
 */
if (!function_exists('worprees_woocommerce_get_cart_total')) {
    function worprees_woocommerce_get_cart_total() {
        if (!worprees_woocommerce_is_active()) {
            return '';
        }
        return WC()->cart->get_cart_total();
    }
}

/**
 * Get WooCommerce cart URL
 */
if (!function_exists('worprees_woocommerce_get_cart_url')) {
    function worprees_woocommerce_get_cart_url() {
        if (!worprees_woocommerce_is_active()) {
            return '';
        }
        return wc_get_cart_url();
    }
}

/**
 * Get WooCommerce checkout URL
 */
if (!function_exists('worprees_woocommerce_get_checkout_url')) {
    function worprees_woocommerce_get_checkout_url() {
        if (!worprees_woocommerce_is_active()) {
            return '';
        }
        return wc_get_checkout_url();
    }
}

/**
 * Get WooCommerce my account URL
 */
if (!function_exists('worprees_woocommerce_get_myaccount_url')) {
    function worprees_woocommerce_get_myaccount_url() {
        if (!worprees_woocommerce_is_active()) {
            return '';
        }
        return wc_get_page_permalink('myaccount');
    }
}

/**
 * Get WooCommerce shop URL
 */
if (!function_exists('worprees_woocommerce_get_shop_url')) {
    function worprees_woocommerce_get_shop_url() {
        if (!worprees_woocommerce_is_active()) {
            return '';
        }
        return wc_get_page_permalink('shop');
    }
}

/**
 * Get WooCommerce product type
 */
if (!function_exists('worprees_woocommerce_get_product_type')) {
    function worprees_woocommerce_get_product_type($product_id) {
        $product = worprees_woocommerce_get_product($product_id);
        if (!$product) {
            return '';
        }
        return $product->get_type();
    }
}

/**
 * Check if WooCommerce product is simple
 */
if (!function_exists('worprees_woocommerce_is_product_simple')) {
    function worprees_woocommerce_is_product_simple($product_id) {
        $product = worprees_woocommerce_get_product($product_id);
        if (!$product) {
            return false;
        }
        return $product->is_type('simple');
    }
}

/**
 * Check if WooCommerce product is variable
 */
if (!function_exists('worprees_woocommerce_is_product_variable')) {
    function worprees_woocommerce_is_product_variable($product_id) {
        $product = worprees_woocommerce_get_product($product_id);
        if (!$product) {
            return false;
        }
        return $product->is_type('variable');
    }
}

/**
 * Get WooCommerce product variations
 */
if (!function_exists('worprees_woocommerce_get_product_variations')) {
    function worprees_woocommerce_get_product_variations($product_id) {
        $product = worprees_woocommerce_get_product($product_id);
        if (!$product || !$product->is_type('variable')) {
            return array();
        }
        return $product->get_available_variations();
    }
}

/**
 * Get WooCommerce product variation price range
 */
if (!function_exists('worprees_woocommerce_get_variation_price_range')) {
    function worprees_woocommerce_get_variation_price_range($product_id) {
        $product = worprees_woocommerce_get_product($product_id);
        if (!$product || !$product->is_type('variable')) {
            return '';
        }
        return $product->get_price_html();
    }
}

/**
 * Get WooCommerce product attributes
 */
if (!function_exists('worprees_woocommerce_get_product_attributes')) {
    function worprees_woocommerce_get_product_attributes($product_id) {
        $product = worprees_woocommerce_get_product($product_id);
        if (!$product) {
            return array();
        }
        return $product->get_attributes();
    }
}

/**
 * Get WooCommerce product weight
 */
if (!function_exists('worprees_woocommerce_get_product_weight')) {
    function worprees_woocommerce_get_product_weight($product_id) {
        $product = worprees_woocommerce_get_product($product_id);
        if (!$product) {
            return '';
        }
        return $product->get_weight();
    }
}

/**
 * Get WooCommerce product dimensions
 */
if (!function_exists('worprees_woocommerce_get_product_dimensions')) {
    function worprees_woocommerce_get_product_dimensions($product_id) {
        $product = worprees_woocommerce_get_product($product_id);
        if (!$product) {
            return array();
        }
        return array(
            'length' => $product->get_length(),
            'width' => $product->get_width(),
            'height' => $product->get_height(),
        );
    }
}

/**
 * Get WooCommerce currency symbol
 */
if (!function_exists('worprees_woocommerce_get_currency_symbol')) {
    function worprees_woocommerce_get_currency_symbol() {
        if (!worprees_woocommerce_is_active()) {
            return 'تومان';
        }
        return get_woocommerce_currency_symbol();
    }
}

/**
 * Persian WooCommerce strings
 */
if (!function_exists('worprees_woocommerce_persian_strings')) {
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
            }
        }
        return $translated_text;
    }
    add_filter('gettext', 'worprees_woocommerce_persian_strings', 10, 3);
}
