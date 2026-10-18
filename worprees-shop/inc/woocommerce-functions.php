<?php
/**
 * Worprees Shop WooCommerce Functions
 * WooCommerce-specific helpers and overrides
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('WooCommerce')) {
    return;
}

// Product data helper
if (!function_exists('worprees_shop_product_data')) {
    function worprees_shop_product_data($product_id = '') {
        if (empty($product_id)) {
            global $product;
            $product_id = $product->get_id();
        }
        
        $product = wc_get_product($product_id);
        
        $data = array(
            'id' => $product->get_id(),
            'name' => $product->get_name(),
            'price' => $product->get_price_html(),
            'regular_price' => $product->get_regular_price(),
            'sale_price' => $product->get_sale_price(),
            'stock_status' => $product->get_stock_status(),
            'stock_quantity' => $product->get_stock_quantity(),
            'rating' => $product->get_average_rating(),
            'review_count' => $product->get_review_count(),
            'sku' => $product->get_sku(),
            'categories' => $product->get_category_ids(),
            'tags' => $product->get_tag_ids(),
            'image' => wp_get_attachment_image_src(get_post_thumbnail_id($product_id), 'worprees-product'),
            'gallery' => $product->get_gallery_image_ids(),
            'short_description' => $product->get_short_description(),
            'description' => $product->get_description(),
        );
        
        return $data;
    }
}

// Product sale badge
if (!function_exists('worprees_shop_product_sale_badge')) {
    function worprees_shop_product_sale_badge($product_id = '') {
        if (empty($product_id)) {
            global $product;
            $product_id = $product->get_id();
        }
        
        $product = wc_get_product($product_id);
        
        if ($product->is_on_sale()) {
            $sale_percentage = 0;
            if ($product->get_regular_price() > 0) {
                $sale_percentage = round((($product->get_regular_price() - $product->get_sale_price()) / $product->get_regular_price()) * 100);
            }
            
            echo '<span class="product-sale-badge">';
            if ($sale_percentage > 0) {
                echo '-' . esc_html($sale_percentage) . '%';
            } else {
                echo '274132482f 2732864632';
            }
            echo '</span>';
        }
    }
}

// Product stock status
if (!function_exists('worprees_shop_product_stock_status')) {
    function worprees_shop_product_stock_status($product_id = '') {
        if (empty($product_id)) {
            global $product;
            $product_id = $product->get_id();
        }
        
        $product = wc_get_product($product_id);
        $stock_status = $product->get_stock_status();
        
        switch ($stock_status) {
            case 'instock':
                echo '<span class="product-stock in-stock">45482c482fcc 27332a48a9</span>';
                break;
            case 'outofstock':
                echo '<span class="product-stock out-of-stock">272a452745 27332a48a9</span>';
                break;
            case 'onbackorder':
                echo '<span class="product-stock on-backorder">2f31 2e31cc2f 27332a48a9</span>';
                break;
        }
    }
}

// Persian currency formatting
if (!function_exists('worprees_shop_persian_currency')) {
    function worprees_shop_persian_currency($price) {
        return str_replace(array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9'), 
                          array('60', '61', '62', '63', '64', '65', '66', '67', '68', '69'),
                          $price);
    }
}

// Custom cart item data
if (!function_exists('worprees_shop_cart_item_data')) {
    function worprees_shop_cart_item_data($item_data, $cart_item) {
        if (is_array($cart_item['variation'])) {
            foreach ($cart_item['variation'] as $name => $value) {
                $name = str_replace('attribute_pa_', '', $name);
                $name = str_replace('attribute_', '', $name);
                $item_data[] = array(
                    'key' => ucfirst($name),
                    'value' => $value,
                );
            }
        }
        return $item_data;
    }
    add_filter('woocommerce_get_item_data', 'worprees_shop_cart_item_data', 10, 2);
}

// Cart widget
if (!function_exists('worprees_shop_cart_widget')) {
    function worprees_shop_cart_widget() {
        echo '<div class="cart-widget">';
        echo '<h3 class="cart-widget-title">33282f 864746cc32</h3>';
        
        if (WC()->cart->is_empty()) {
            echo '<p class="cart-empty">33282f 864746cc32 2e2744cc 27332a</p>';
        } else {
            echo '<ul class="cart-widget-items">';
            foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                $product = $cart_item['data'];
                echo '<li class="cart-widget-item">';
                echo '<a href="' . esc_url(get_permalink($cart_item['product_id'])) . '" class="cart-widget-item-link">';
                echo get_the_post_thumbnail($cart_item['product_id'], 'worprees-thumbnail');
                echo '<div class="cart-widget-item-info">';
                echo '<span class="cart-widget-item-name">' . esc_html($product->get_name()) . '</span>';
                echo '<span class="cart-widget-item-price">' . $product->get_price_html() . '</span>';
                echo '<span class="cart-widget-item-quantity">' . esc_html($cart_item['quantity']) . '</span>';
                echo '</div>';
                echo '</a>';
                echo '</li>';
            }
            echo '</ul>';
            
            echo '<div class="cart-widget-footer">';
            echo '<span class="cart-widget-total">452c454839: ' . WC()->cart->get_cart_total() . '</span>';
            echo '<a href="' . esc_url(wc_get_cart_url()) . '" class="cart-widget-view-cart">453427472f47 33282d</a>';
            echo '<a href="' . esc_url(wc_get_checkout_url()) . '" class="cart-widget-checkout">2a2e35cc2f 28cc2b</a>';
            echo '</div>';
        }
        
        echo '</div>';
    }
}

// Product tabs
if (!function_exists('worprees_shop_product_tabs')) {
    function worprees_shop_product_tabs($tabs) {
        $tabs['description']['title'] = '2a4836cc45272a';
        $tabs['reviews']['title'] = '46382731272a';
        return $tabs;
    }
    add_filter('woocommerce_product_tabs', 'worprees_shop_product_tabs');
}

// Related products
if (!function_exists('worprees_shop_related_products_args')) {
    function worprees_shop_related_products_args($args) {
        $args['posts_per_page'] = 4;
        $args['columns'] = 4;
        return $args;
    }
    add_filter('woocommerce_output_related_products_args', 'worprees_shop_related_products_args');
}

// Upsell products
if (!function_exists('worprees_shop_upsell_products_args')) {
    function worprees_shop_upsell_products_args($args) {
        $args['posts_per_page'] = 4;
        $args['columns'] = 4;
        return $args;
    }
    add_filter('woocommerce_upsell_display_args', 'worprees_shop_upsell_products_args');
}

// Cross sell products
if (!function_exists('worprees_shop_cross_sell_products_args')) {
    function worprees_shop_cross_sell_products_args($args) {
        $args['posts_per_page'] = 4;
        $args['columns'] = 4;
        return $args;
    }
    add_filter('woocommerce_cross_sells_columns', 'worprees_shop_cross_sell_products_args');
}

// Checkout fields
if (!function_exists('worprees_shop_checkout_fields')) {
    function worprees_shop_checkout_fields($fields) {
        $fields['billing']['billing_first_name']['label'] = '462745';
        $fields['billing']['billing_last_name']['label'] = '462745 86482746cc48';
        $fields['billing']['billing_company']['label'] = '3431a92a';
        $fields['billing']['billing_address_1']['label'] = '222f3133';
        $fields['billing']['billing_address_2']['label'] = '222f3133 2';
        $fields['billing']['billing_city']['label'] = '344731';
        $fields['billing']['billing_state']['label'] = '27332a2746';
        $fields['billing']['billing_postcode']['label'] = '2b2f 7e342acc';
        $fields['billing']['billing_country']['label'] = 'a9344831';
        $fields['billing']['billing_email']['label'] = '27cc45cc44';
        $fields['billing']['billing_phone']['label'] = '2a444146';
        
        return $fields;
    }
    add_filter('woocommerce_checkout_fields', 'worprees_shop_checkout_fields');
}

// Persian strings
if (!function_exists('worprees_shop_persian_strings')) {
    function worprees_shop_persian_strings($translated_text, $text, $domain) {
        $persian_strings = array(
            'Add to cart' => '274132482f 2847 33282f 864746cc32',
            'View cart' => '453427472f47 33282d',
            'Checkout' => '2a2e35cc2f 28cc2b',
            'Product' => '45454844',
            'Price' => '42cc452a',
            'Quantity' => '2a2f272f',
            'Subtotal' => '452c454839',
            'Total' => '452c454839 2e44',
            'Proceed to checkout' => '2a2f2745 2847 28cc28',
            'Place order' => '2b2e2f48 3341273134',
            'Your order' => '3341273134 344527',
            'Billing details' => '2c32cc272a 452e273528',
            'Shipping address' => '222f3133 2731332733',
            'Payment method' => '314834 7e312f273286',
            'Order notes' => '4a272f2f27342a2a 3341273134',
        );
        
        if (array_key_exists($text, $persian_strings) && 'woocommerce' === $domain) {
            return $persian_strings[$text];
        }
        
        return $translated_text;
    }
    add_filter('gettext', 'worprees_shop_persian_strings', 20, 3);
}
