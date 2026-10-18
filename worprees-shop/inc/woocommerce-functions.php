<?php
/**
 * Worprees Theme WooCommerce Functions
 * 100+ helper functions for WooCommerce integration
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Check if WooCommerce is active
 */
if (!function_exists('worprees_is_woocommerce_active')) {
    function worprees_is_woocommerce_active() {
        return class_exists('WooCommerce');
    }
}

/**
 * Check if WooCommerce page
 */
if (!function_exists('worprees_is_woocommerce_page')) {
    function worprees_is_woocommerce_page() {
        if (!worprees_is_woocommerce_active()) {
            return false;
        }
        
        if (is_woocommerce()) {
            return true;
        }
        
        if (is_cart()) {
            return true;
        }
        
        if (is_checkout()) {
            return true;
        }
        
        if (is_account_page()) {
            return true;
        }
        
        if (is_wc_endpoint_url()) {
            return true;
        }
        
        return false;
    }
}

/**
 * Get WooCommerce version
 */
if (!function_exists('worprees_get_woocommerce_version')) {
    function worprees_get_woocommerce_version() {
        if (!worprees_is_woocommerce_active()) {
            return null;
        }
        
        return WC()->version;
    }
}

/**
 * Get WooCommerce currency
 */
if (!function_exists('worprees_get_woocommerce_currency')) {
    function worprees_get_woocommerce_currency() {
        if (!worprees_is_woocommerce_active()) {
            return '';
        }
        
        return get_woocommerce_currency();
    }
}

/**
 * Get WooCommerce currency symbol
 */
if (!function_exists('worprees_get_woocommerce_currency_symbol')) {
    function worprees_get_woocommerce_currency_symbol() {
        if (!worprees_is_woocommerce_active()) {
            return '';
        }
        
        return get_woocommerce_currency_symbol();
    }
}

/**
 * Get WooCommerce price format
 */
if (!function_exists('worprees_get_woocommerce_price_format')) {
    function worprees_get_woocommerce_price_format() {
        if (!worprees_is_woocommerce_active()) {
            return '';
        }
        
        return get_woocommerce_price_format();
    }
}

/**
 * Get WooCommerce decimal separator
 */
if (!function_exists('worprees_get_woocommerce_decimal_separator')) {
    function worprees_get_woocommerce_decimal_separator() {
        if (!worprees_is_woocommerce_active()) {
            return ',';
        }
        
        return wc_get_price_decimal_separator();
    }
}

/**
 * Get WooCommerce thousand separator
 */
if (!function_exists('worprees_get_woocommerce_thousand_separator')) {
    function worprees_get_woocommerce_thousand_separator() {
        if (!worprees_is_woocommerce_active()) {
            return ',';
        }
        
        return wc_get_price_thousand_separator();
    }
}

/**
 * Get WooCommerce number of decimals
 */
if (!function_exists('worprees_get_woocommerce_number_of_decimals')) {
    function worprees_get_woocommerce_number_of_decimals() {
        if (!worprees_is_woocommerce_active()) {
            return 2;
        }
        
        return wc_get_price_decimals();
    }
}

/**
 * Get WooCommerce tax display mode
 */
if (!function_exists('worprees_get_woocommerce_tax_display_mode')) {
    function worprees_get_woocommerce_tax_display_mode() {
        if (!worprees_is_woocommerce_active()) {
            return '';
        }
        
        return get_option('woocommerce_tax_display_shop');
    }
}

/**
 * Get WooCommerce shipping display mode
 */
if (!function_exists('worprees_get_woocommerce_shipping_display_mode')) {
    function worprees_get_woocommerce_shipping_display_mode() {
        if (!worprees_is_woocommerce_active()) {
            return '';
        }
        
        return get_option('woocommerce_shipping_display_mode');
    }
}

/**
 * Get WooCommerce default country
 */
if (!function_exists('worprees_get_woocommerce_default_country')) {
    function worprees_get_woocommerce_default_country() {
        if (!worprees_is_woocommerce_active()) {
            return '';
        }
        
        return WC()->countries->get_base_country();
    }
}

/**
 * Get WooCommerce base state
 */
if (!function_exists('worprees_get_woocommerce_default_state')) {
    function worprees_get_woocommerce_default_state() {
        if (!worprees_is_woocommerce_active()) {
            return '';
        }
        
        return WC()->countries->get_base_state();
    }
}

/**
 * Get WooCommerce countries
 */
if (!function_exists('worprees_get_woocommerce_countries')) {
    function worprees_get_woocommerce_countries() {
        if (!worprees_is_woocommerce_active()) {
            return array();
        }
        
        return WC()->countries->get_countries();
    }
}

/**
 * Get WooCommerce states
 */
if (!function_exists('worprees_get_woocommerce_states')) {
    function worprees_get_woocommerce_states($country_code) {
        if (!worprees_is_woocommerce_active()) {
            return array();
        }
        
        return WC()->countries->get_states($country_code);
    }
}

/**
 * Get WooCommerce shipping zones
 */
if (!function_exists('worprees_get_woocommerce_shipping_zones')) {
    function worprees_get_woocommerce_shipping_zones() {
        if (!worprees_is_woocommerce_active()) {
            return array();
        }
        
        return WC_Shipping_Zones::get_zones();
    }
}

/**
 * Get WooCommerce shipping methods
 */
if (!function_exists('worprees_get_woocommerce_shipping_methods')) {
    function worprees_get_woocommerce_shipping_methods() {
        if (!worprees_is_woocommerce_active()) {
            return array();
        }
        
        return WC()->shipping->get_shipping_methods();
    }
}

/**
 * Get WooCommerce payment gateways
 */
if (!function_exists('worprees_get_woocommerce_payment_gateways')) {
    function worprees_get_woocommerce_payment_gateways() {
        if (!worprees_is_woocommerce_active()) {
            return array();
        }
        
        return WC()->payment_gateways->get_available_payment_gateways();
    }
}

/**
 * Get WooCommerce enabled payment gateways
 */
if (!function_exists('worprees_get_woocommerce_enabled_payment_gateways')) {
    function worprees_get_woocommerce_enabled_payment_gateways() {
        if (!worprees_is_woocommerce_active()) {
            return array();
        }
        
        return WC()->payment_gateways->get_enabled_payment_gateways();
    }
}

/**
 * Get WooCommerce cart
 */
if (!function_exists('worprees_get_woocommerce_cart')) {
    function worprees_get_woocommerce_cart() {
        if (!worprees_is_woocommerce_active()) {
            return null;
        }
        
        return WC()->cart;
    }
}

/**
 * Get WooCommerce cart items
 */
if (!function_exists('worprees_get_woocommerce_cart_items')) {
    function worprees_get_woocommerce_cart_items() {
        $cart = worprees_get_woocommerce_cart();
        if (!$cart) {
            return array();
        }
        
        return $cart->get_cart();
    }
}

/**
 * Get WooCommerce cart count
 */
if (!function_exists('worprees_get_woocommerce_cart_count')) {
    function worprees_get_woocommerce_cart_count() {
        $cart = worprees_get_woocommerce_cart();
        if (!$cart) {
            return 0;
        }
        
        return $cart->get_cart_contents_count();
    }
}

/**
 * Get WooCommerce cart total
 */
if (!function_exists('worprees_get_woocommerce_cart_total')) {
    function worprees_get_woocommerce_cart_total() {
        $cart = worprees_get_woocommerce_cart();
        if (!$cart) {
            return '';
        }
        
        return $cart->get_cart_total();
    }
}

/**
 * Get WooCommerce cart subtotal
 */
if (!function_exists('worprees_get_woocommerce_cart_subtotal')) {
    function worprees_get_woocommerce_cart_subtotal() {
        $cart = worprees_get_woocommerce_cart();
        if (!$cart) {
            return '';
        }
        
        return $cart->get_cart_subtotal();
    }
}

/**
 * Get WooCommerce cart tax
 */
if (!function_exists('worprees_get_woocommerce_cart_tax')) {
    function worprees_get_woocommerce_cart_tax() {
        $cart = worprees_get_woocommerce_cart();
        if (!$cart) {
            return '';
        }
        
        return $cart->get_cart_tax();
    }
}

/**
 * Get WooCommerce cart shipping total
 */
if (!function_exists('worprees_get_woocommerce_cart_shipping_total')) {
    function worprees_get_woocommerce_cart_shipping_total() {
        $cart = worprees_get_woocommerce_cart();
        if (!$cart) {
            return '';
        }
        
        return $cart->get_cart_shipping_total();
    }
}

/**
 * Get WooCommerce cart discount total
 */
if (!function_exists('worprees_get_woocommerce_cart_discount_total')) {
    function worprees_get_woocommerce_cart_discount_total() {
        $cart = worprees_get_woocommerce_cart();
        if (!$cart) {
            return '';
        }
        
        return $cart->get_cart_discount_total();
    }
}

/**
 * Get WooCommerce cart discount amount
 */
if (!function_exists('worprees_get_woocommerce_cart_discount_amount')) {
    function worprees_get_woocommerce_cart_discount_amount() {
        $cart = worprees_get_woocommerce_cart();
        if (!$cart) {
            return '';
        }
        
        return $cart->get_cart_discount_amount();
    }
}

/**
 * Get WooCommerce cart fee total
 */
if (!function_exists('worprees_get_woocommerce_cart_fee_total')) {
    function worprees_get_woocommerce_cart_fee_total() {
        $cart = worprees_get_woocommerce_cart();
        if (!$cart) {
            return '';
        }
        
        return $cart->get_fee_total();
    }
}

/**
 * Get WooCommerce cart total weight
 */
if (!function_exists('worprees_get_woocommerce_cart_total_weight')) {
    function worprees_get_woocommerce_cart_total_weight() {
        $cart = worprees_get_woocommerce_cart();
        if (!$cart) {
            return '';
        }
        
        return $cart->get_cart_contents_weight();
    }
}

/**
 * Check if WooCommerce cart is empty
 */
if (!function_exists('worprees_is_woocommerce_cart_empty')) {
    function worprees_is_woocommerce_cart_empty() {
        $cart = worprees_get_woocommerce_cart();
        if (!$cart) {
            return true;
        }
        
        return $cart->is_empty();
    }
}

/**
 * Get WooCommerce checkout
 */
if (!function_exists('worprees_get_woocommerce_checkout')) {
    function worprees_get_woocommerce_checkout() {
        if (!worprees_is_woocommerce_active()) {
            return null;
        }
        
        return WC()->checkout();
    }
}

/**
 * Get WooCommerce checkout URL
 */
if (!function_exists('worprees_get_woocommerce_checkout_url')) {
    function worprees_get_woocommerce_checkout_url() {
        if (!worprees_is_woocommerce_active()) {
            return '';
        }
        
        return wc_get_checkout_url();
    }
}

/**
 * Get WooCommerce cart URL
 */
if (!function_exists('worprees_get_woocommerce_cart_url')) {
    function worprees_get_woocommerce_cart_url() {
        if (!worprees_is_woocommerce_active()) {
            return '';
        }
        
        return wc_get_cart_url();
    }
}

/**
 * Get WooCommerce my account URL
 */
if (!function_exists('worprees_get_woocommerce_my_account_url')) {
    function worprees_get_woocommerce_my_account_url() {
        if (!worprees_is_woocommerce_active()) {
            return '';
        }
        
        return wc_get_page_permalink('myaccount');
    }
}

/**
 * Get WooCommerce shop URL
 */
if (!function_exists('worprees_get_woocommerce_shop_url')) {
    function worprees_get_woocommerce_shop_url() {
        if (!worprees_is_woocommerce_active()) {
            return '';
        }
        
        return wc_get_page_permalink('shop');
    }
}

/**
 * Get WooCommerce product
 */
if (!function_exists('worprees_get_woocommerce_product')) {
    function worprees_get_woocommerce_product($product_id) {
        if (!worprees_is_woocommerce_active()) {
            return null;
        }
        
        return wc_get_product($product_id);
    }
}

/**
 * Get WooCommerce product price
 */
if (!function_exists('worprees_get_woocommerce_product_price')) {
    function worprees_get_woocommerce_product_price($product_id) {
        $product = worprees_get_woocommerce_product($product_id);
        if (!$product) {
            return '';
        }
        
        return $product->get_price_html();
    }
}

/**
 * Get WooCommerce product regular price
 */
if (!function_exists('worprees_get_woocommerce_product_regular_price')) {
    function worprees_get_woocommerce_product_regular_price($product_id) {
        $product = worprees_get_woocommerce_product($product_id);
        if (!$product) {
            return '';
        }
        
        return $product->get_regular_price();
    }
}

/**
 * Get WooCommerce product sale price
 */
if (!function_exists('worprees_get_woocommerce_product_sale_price')) {
    function worprees_get_woocommerce_product_sale_price($product_id) {
        $product = worprees_get_woocommerce_product($product_id);
        if (!$product) {
            return '';
        }
        
        return $product->get_sale_price();
    }
}

/**
 * Get WooCommerce product price without HTML
 */
if (!function_exists('worprees_get_woocommerce_product_price_without_html')) {
    function worprees_get_woocommerce_product_price_without_html($product_id) {
        $product = worprees_get_woocommerce_product($product_id);
        if (!$product) {
            return '';
        }
        
        return strip_tags($product->get_price_html());
    }
}

/**
 * Get WooCommerce product average rating
 */
if (!function_exists('worprees_get_woocommerce_product_average_rating')) {
    function worprees_get_woocommerce_product_average_rating($product_id) {
        $product = worprees_get_woocommerce_product($product_id);
        if (!$product) {
            return 0;
        }
        
        return $product->get_average_rating();
    }
}

/**
 * Get WooCommerce product review count
 */
if (!function_exists('worprees_get_woocommerce_product_review_count')) {
    function worprees_get_woocommerce_product_review_count($product_id) {
        $product = worprees_get_woocommerce_product($product_id);
        if (!$product) {
            return 0;
        }
        
        return $product->get_review_count();
    }
}

/**
 * Get WooCommerce product stock status
 */
if (!function_exists('worprees_get_woocommerce_product_stock_status')) {
    function worprees_get_woocommerce_product_stock_status($product_id) {
        $product = worprees_get_woocommerce_product($product_id);
        if (!$product) {
            return '';
        }
        
        return $product->get_stock_status();
    }
}

/**
 * Get WooCommerce product stock quantity
 */
if (!function_exists('worprees_get_woocommerce_product_stock_quantity')) {
    function worprees_get_woocommerce_product_stock_quantity($product_id) {
        $product = worprees_get_woocommerce_product($product_id);
        if (!$product) {
            return 0;
        }
        
        return $product->get_stock_quantity();
    }
}

/**
 * Check if WooCommerce product is in stock
 */
if (!function_exists('worprees_is_woocommerce_product_in_stock')) {
    function worprees_is_woocommerce_product_in_stock($product_id) {
        $product = worprees_get_woocommerce_product($product_id);
        if (!$product) {
            return false;
        }
        
        return $product->is_in_stock();
    }
}

/**
 * Check if WooCommerce product is on sale
 */
if (!function_exists('worprees_is_woocommerce_product_on_sale')) {
    function worprees_is_woocommerce_product_on_sale($product_id) {
        $product = worprees_get_woocommerce_product($product_id);
        if (!$product) {
            return false;
        }
        
        return $product->is_on_sale();
    }
}

/**
 * Check if WooCommerce product is featured
 */
if (!function_exists('worprees_is_woocommerce_product_featured')) {
    function worprees_is_woocommerce_product_featured($product_id) {
        $product = worprees_get_woocommerce_product($product_id);
        if (!$product) {
            return false;
        }
        
        return $product->is_featured();
    }
}

/**
 * Check if WooCommerce product is visible
 */
if (!function_exists('worprees_is_woocommerce_product_visible')) {
    function worprees_is_woocommerce_product_visible($product_id) {
        $product = worprees_get_woocommerce_product($product_id);
        if (!$product) {
            return false;
        }
        
        return $product->is_visible();
    }
}

/**
 * Check if WooCommerce product is purchasable
 */
if (!function_exists('worprees_is_woocommerce_product_purchasable')) {
    function worprees_is_woocommerce_product_purchasable($product_id) {
        $product = worprees_get_woocommerce_product($product_id);
        if (!$product) {
            return false;
        }
        
        return $product->is_purchasable();
    }
}

/**
 * Get WooCommerce product categories
 */
if (!function_exists('worprees_get_woocommerce_product_categories')) {
    function worprees_get_woocommerce_product_categories($product_id) {
        $product = worprees_get_woocommerce_product($product_id);
        if (!$product) {
            return array();
        }
        
        return $product->get_category_ids();
    }
}

/**
 * Get WooCommerce product tags
 */
if (!function_exists('worprees_get_woocommerce_product_tags')) {
    function worprees_get_woocommerce_product_tags($product_id) {
        $product = worprees_get_woocommerce_product($product_id);
        if (!$product) {
            return array();
        }
        
        return $product->get_tag_ids();
    }
}

/**
 * Get WooCommerce product attributes
 */
if (!function_exists('worprees_get_woocommerce_product_attributes')) {
    function worprees_get_woocommerce_product_attributes($product_id) {
        $product = worprees_get_woocommerce_product($product_id);
        if (!$product) {
            return array();
        }
        
        return $product->get_attributes();
    }
}

/**
 * Get WooCommerce product variations
 */
if (!function_exists('worprees_get_woocommerce_product_variations')) {
    function worprees_get_woocommerce_product_variations($product_id) {
        $product = worprees_get_woocommerce_product($product_id);
        if (!$product || !$product->is_type('variable')) {
            return array();
        }
        
        return $product->get_available_variations();
    }
}

/**
 * Get WooCommerce product variation price range
 */
if (!function_exists('worprees_get_woocommerce_product_variation_price_range')) {
    function worprees_get_woocommerce_product_variation_price_range($product_id) {
        $product = worprees_get_woocommerce_product($product_id);
        if (!$product || !$product->is_type('variable')) {
            return '';
        }
        
        return $product->get_price_html();
    }
}

/**
 * Get WooCommerce product image
 */
if (!function_exists('worprees_get_woocommerce_product_image')) {
    function worprees_get_woocommerce_product_image($product_id, $size = 'shop_thumbnail') {
        $product = worprees_get_woocommerce_product($product_id);
        if (!$product) {
            return '';
        }
        
        return $product->get_image($size);
    }
}

/**
 * Get WooCommerce product image URL
 */
if (!function_exists('worprees_get_woocommerce_product_image_url')) {
    function worprees_get_woocommerce_product_image_url($product_id, $size = 'shop_thumbnail') {
        $product = worprees_get_woocommerce_product($product_id);
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
if (!function_exists('worprees_get_woocommerce_product_gallery_images')) {
    function worprees_get_woocommerce_product_gallery_images($product_id) {
        $product = worprees_get_woocommerce_product($product_id);
        if (!$product) {
            return array();
        }
        
        return $product->get_gallery_image_ids();
    }
}

/**
 * Get WooCommerce product short description
 */
if (!function_exists('worprees_get_woocommerce_product_short_description')) {
    function worprees_get_woocommerce_product_short_description($product_id) {
        $product = worprees_get_woocommerce_product($product_id);
        if (!$product) {
            return '';
        }
        
        return $product->get_short_description();
    }
}

/**
 * Get WooCommerce product description
 */
if (!function_exists('worprees_get_woocommerce_product_description')) {
    function worprees_get_woocommerce_product_description($product_id) {
        $product = worprees_get_woocommerce_product($product_id);
        if (!$product) {
            return '';
        }
        
        return $product->get_description();
    }
}

/**
 * Get WooCommerce product SKU
 */
if (!function_exists('worprees_get_woocommerce_product_sku')) {
    function worprees_get_woocommerce_product_sku($product_id) {
        $product = worprees_get_woocommerce_product($product_id);
        if (!$product) {
            return '';
        }
        
        return $product->get_sku();
    }
}

/**
 * Get WooCommerce product weight
 */
if (!function_exists('worprees_get_woocommerce_product_weight')) {
    function worprees_get_woocommerce_product_weight($product_id) {
        $product = worprees_get_woocommerce_product($product_id);
        if (!$product) {
            return '';
        }
        
        return $product->get_weight();
    }
}

/**
 * Get WooCommerce product dimensions
 */
if (!function_exists('worprees_get_woocommerce_product_dimensions')) {
    function worprees_get_woocommerce_product_dimensions($product_id) {
        $product = worprees_get_woocommerce_product($product_id);
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
 * Get WooCommerce product shipping class
 */
if (!function_exists('worprees_get_woocommerce_product_shipping_class')) {
    function worprees_get_woocommerce_product_shipping_class($product_id) {
        $product = worprees_get_woocommerce_product($product_id);
        if (!$product) {
            return '';
        }
        
        return $product->get_shipping_class();
    }
}

/**
 * Get WooCommerce product shipping class ID
 */
if (!function_exists('worprees_get_woocommerce_product_shipping_class_id')) {
    function worprees_get_woocommerce_product_shipping_class_id($product_id) {
        $product = worprees_get_woocommerce_product($product_id);
        if (!$product) {
            return 0;
        }
        
        return $product->get_shipping_class_id();
    }
}

/**
 * Get WooCommerce product tax class
 */
if (!function_exists('worprees_get_woocommerce_product_tax_class')) {
    function worprees_get_woocommerce_product_tax_class($product_id) {
        $product = worprees_get_woocommerce_product($product_id);
        if (!$product) {
            return '';
        }
        
        return $product->get_tax_class();
    }
}

/**
 * Get WooCommerce product tax status
 */
if (!function_exists('worprees_get_woocommerce_product_tax_status')) {
    function worprees_get_woocommerce_product_tax_status($product_id) {
        $product = worprees_get_woocommerce_product($product_id);
        if (!$product) {
            return '';
        }
        
        return $product->get_tax_status();
    }
}

/**
 * Get WooCommerce product downloadable
 */
if (!function_exists('worprees_is_woocommerce_product_downloadable')) {
    function worprees_is_woocommerce_product_downloadable($product_id) {
        $product = worprees_get_woocommerce_product($product_id);
        if (!$product) {
            return false;
        }
        
        return $product->is_downloadable();
    }
}

/**
 * Get WooCommerce product virtual
 */
if (!function_exists('worprees_is_woocommerce_product_virtual')) {
    function worprees_is_woocommerce_product_virtual($product_id) {
        $product = worprees_get_woocommerce_product($product_id);
        if (!$product) {
            return false;
        }
        
        return $product->is_virtual();
    }
}

/**
 * Get WooCommerce product sold individually
 */
if (!function_exists('worprees_is_woocommerce_product_sold_individually')) {
    function worprees_is_woocommerce_product_sold_individually($product_id) {
        $product = worprees_get_woocommerce_product($product_id);
        if (!$product) {
            return false;
        }
        
        return $product->is_sold_individually();
    }
}

/**
 * Get WooCommerce product backorders allowed
 */
if (!function_exists('worprees_get_woocommerce_product_backorders_allowed')) {
    function worprees_get_woocommerce_product_backorders_allowed($product_id) {
        $product = worprees_get_woocommerce_product($product_id);
        if (!$product) {
            return '';
        }
        
        return $product->get_backorders();
    }
}

/**
 * Get WooCommerce product reviews allowed
 */
if (!function_exists('worprees_get_woocommerce_product_reviews_allowed')) {
    function worprees_get_woocommerce_product_reviews_allowed($product_id) {
        $product = worprees_get_woocommerce_product($product_id);
        if (!$product) {
            return false;
        }
        
        return $product->get_reviews_allowed();
    }
}

/**
 * Get WooCommerce product menu order
 */
if (!function_exists('worprees_get_woocommerce_product_menu_order')) {
    function worprees_get_woocommerce_product_menu_order($product_id) {
        $product = worprees_get_woocommerce_product($product_id);
        if (!$product) {
            return 0;
        }
        
        return $product->get_menu_order();
    }
}

/**
 * Get WooCommerce product date created
 */
if (!function_exists('worprees_get_woocommerce_product_date_created')) {
    function worprees_get_woocommerce_product_date_created($product_id) {
        $product = worprees_get_woocommerce_product($product_id);
        if (!$product) {
            return '';
        }
        
        return $product->get_date_created();
    }
}

/**
 * Get WooCommerce product date modified
 */
if (!function_exists('worprees_get_woocommerce_product_date_modified')) {
    function worprees_get_woocommerce_product_date_modified($product_id) {
        $product = worprees_get_woocommerce_product($product_id);
        if (!$product) {
            return '';
        }
        
        return $product->get_date_modified();
    }
}

/**
 * Get WooCommerce product type
 */
if (!function_exists('worprees_get_woocommerce_product_type')) {
    function worprees_get_woocommerce_product_type($product_id) {
        $product = worprees_get_woocommerce_product($product_id);
        if (!$product) {
            return '';
        }
        
        return $product->get_type();
    }
}

/**
 * Check if WooCommerce product is simple
 */
if (!function_exists('worprees_is_woocommerce_product_simple')) {
    function worprees_is_woocommerce_product_simple($product_id) {
        $product = worprees_get_woocommerce_product($product_id);
        if (!$product) {
            return false;
        }
        
        return $product->is_type('simple');
    }
}

/**
 * Check if WooCommerce product is variable
 */
if (!function_exists('worprees_is_woocommerce_product_variable')) {
    function worprees_is_woocommerce_product_variable($product_id) {
        $product = worprees_get_woocommerce_product($product_id);
        if (!$product) {
            return false;
        }
        
        return $product->is_type('variable');
    }
}

/**
 * Check if WooCommerce product is grouped
 */
if (!function_exists('worprees_is_woocommerce_product_grouped')) {
    function worprees_is_woocommerce_product_grouped($product_id) {
        $product = worprees_get_woocommerce_product($product_id);
        if (!$product) {
            return false;
        }
        
        return $product->is_type('grouped');
    }
}

/**
 * Check if WooCommerce product is external
 */
if (!function_exists('worprees_is_woocommerce_product_external')) {
    function worprees_is_woocommerce_product_external($product_id) {
        $product = worprees_get_woocommerce_product($product_id);
        if (!$product) {
            return false;
        }
        
        return $product->is_type('external');
    }
}
