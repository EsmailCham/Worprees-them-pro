<?php
/**
 * Worprees Shop AJAX Functions
 * AJAX handlers for frontend interactions
 */

if (!defined('ABSPATH')) {
    exit;
}

// Quick view product
if (!function_exists('worprees_shop_quick_view')) {
    add_action('wp_ajax_worprees_quick_view', 'worprees_shop_quick_view');
    add_action('wp_ajax_nopriv_worprees_quick_view', 'worprees_shop_quick_view');
    
    function worprees_shop_quick_view() {
        check_ajax_referer('worprees_nonce', 'nonce');
        
        if (!isset($_POST['product_id']) || !class_exists('WooCommerce')) {
            wp_send_json_error();
        }
        
        $product_id = intval($_POST['product_id']);
        $product = wc_get_product($product_id);
        
        if (!$product) {
            wp_send_json_error();
        }
        
        ob_start();
        
        echo '<div class="quick-view-product">';
        echo '<div class="quick-view-product-image">';
        echo get_the_post_thumbnail($product_id, 'worprees-medium');
        echo '</div>';
        echo '<div class="quick-view-product-info">';
        echo '<h3 class="quick-view-product-title">' . esc_html($product->get_name()) . '</h3>';
        echo '<div class="quick-view-product-price">' . $product->get_price_html() . '</div>';
        echo '<div class="quick-view-product-rating">';
        if ($product->get_average_rating()) {
            wc_get_template('single-product/rating.php', array('rating' => $product->get_average_rating()));
        }
        echo '</div>';
        echo '<div class="quick-view-product-excerpt">' . wp_kses_post($product->get_short_description());
        echo '</div>';
        echo '<div class="quick-view-product-actions">';
        woocommerce_quantity_input(array(), $product);
        echo '<button class="quick-view-add-to-cart button">274132482f46 2847 33282f 864746cc32</button>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        
        $html = ob_get_clean();
        wp_send_json_success(array('html' => $html));
    }
}

// Add to cart AJAX
if (!function_exists('worprees_shop_add_to_cart_ajax')) {
    add_action('wp_ajax_worprees_add_to_cart', 'worprees_shop_add_to_cart_ajax');
    add_action('wp_ajax_nopriv_worprees_add_to_cart', 'worprees_shop_add_to_cart_ajax');
    
    function worprees_shop_add_to_cart_ajax() {
        check_ajax_referer('worprees_nonce', 'nonce');
        
        if (!isset($_POST['product_id']) || !class_exists('WooCommerce')) {
            wp_send_json_error();
        }
        
        $product_id = intval($_POST['product_id']);
        $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
        
        WC()->cart->add_to_cart($product_id, $quantity);
        
        wp_send_json_success(array(
            'cart_count' => WC()->cart->get_cart_contents_count(),
            'cart_total' => WC()->cart->get_cart_total(),
        ));
    }
}

// Wishlist
if (!function_exists('worprees_shop_add_to_wishlist')) {
    add_action('wp_ajax_worprees_add_to_wishlist', 'worprees_shop_add_to_wishlist');
    add_action('wp_ajax_nopriv_worprees_add_to_wishlist', 'worprees_shop_add_to_wishlist');
    
    function worprees_shop_add_to_wishlist() {
        check_ajax_referer('worprees_nonce', 'nonce');
        
        if (!isset($_POST['product_id'])) {
            wp_send_json_error();
        }
        
        $product_id = intval($_POST['product_id']);
        
        if (!isset($_COOKIE['worprees_wishlist'])) {
            $wishlist = array();
        } else {
            $wishlist = json_decode(stripslashes($_COOKIE['worprees_wishlist']), true);
        }
        
        if (!in_array($product_id, $wishlist)) {
            $wishlist[] = $product_id;
            setcookie('worprees_wishlist', json_encode($wishlist), time() + 30 * DAY_IN_SECONDS, '/');
        }
        
        wp_send_json_success(array(
            'wishlist_count' => count($wishlist),
        ));
    }
}

// Remove from wishlist
if (!function_exists('worprees_shop_remove_from_wishlist')) {
    add_action('wp_ajax_worprees_remove_from_wishlist', 'worprees_shop_remove_from_wishlist');
    add_action('wp_ajax_nopriv_worprees_remove_from_wishlist', 'worprees_shop_remove_from_wishlist');
    
    function worprees_shop_remove_from_wishlist() {
        check_ajax_referer('worprees_nonce', 'nonce');
        
        if (!isset($_POST['product_id'])) {
            wp_send_json_error();
        }
        
        $product_id = intval($_POST['product_id']);
        
        if (!isset($_COOKIE['worprees_wishlist'])) {
            wp_send_json_success();
        }
        
        $wishlist = json_decode(stripslashes($_COOKIE['worprees_wishlist']), true);
        $key = array_search($product_id, $wishlist);
        
        if ($key !== false) {
            unset($wishlist[$key]);
            setcookie('worprees_wishlist', json_encode(array_values($wishlist)), time() + 30 * DAY_IN_SECONDS, '/');
        }
        
        wp_send_json_success(array(
            'wishlist_count' => count($wishlist),
        ));
    }
}

// Compare
if (!function_exists('worprees_shop_add_to_compare')) {
    add_action('wp_ajax_worprees_add_to_compare', 'worprees_shop_add_to_compare');
    add_action('wp_ajax_nopriv_worprees_add_to_compare', 'worprees_shop_add_to_compare');
    
    function worprees_shop_add_to_compare() {
        check_ajax_referer('worprees_nonce', 'nonce');
        
        if (!isset($_POST['product_id'])) {
            wp_send_json_error();
        }
        
        $product_id = intval($_POST['product_id']);
        
        if (!isset($_COOKIE['worprees_compare'])) {
            $compare = array();
        } else {
            $compare = json_decode(stripslashes($_COOKIE['worprees_compare']), true);
        }
        
        if (!in_array($product_id, $compare)) {
            $compare[] = $product_id;
            setcookie('worprees_compare', json_encode($compare), time() + 30 * DAY_IN_SECONDS, '/');
        }
        
        wp_send_json_success(array(
            'compare_count' => count($compare),
        ));
    }
}

// Remove from compare
if (!function_exists('worprees_shop_remove_from_compare')) {
    add_action('wp_ajax_worprees_remove_from_compare', 'worprees_shop_remove_from_compare');
    add_action('wp_ajax_nopriv_worprees_remove_from_compare', 'worprees_shop_remove_from_compare');
    
    function worprees_shop_remove_from_compare() {
        check_ajax_referer('worprees_nonce', 'nonce');
        
        if (!isset($_POST['product_id'])) {
            wp_send_json_error();
        }
        
        $product_id = intval($_POST['product_id']);
        
        if (!isset($_COOKIE['worprees_compare'])) {
            wp_send_json_success();
        }
        
        $compare = json_decode(stripslashes($_COOKIE['worprees_compare']), true);
        $key = array_search($product_id, $compare);
        
        if ($key !== false) {
            unset($compare[$key]);
            setcookie('worprees_compare', json_encode(array_values($compare)), time() + 30 * DAY_IN_SECONDS, '/');
        }
        
        wp_send_json_success(array(
            'compare_count' => count($compare),
        ));
    }
}

// Newsletter subscription
if (!function_exists('worprees_shop_newsletter_subscribe')) {
    add_action('wp_ajax_worprees_newsletter_subscribe', 'worprees_shop_newsletter_subscribe');
    add_action('wp_ajax_nopriv_worprees_newsletter_subscribe', 'worprees_shop_newsletter_subscribe');
    
    function worprees_shop_newsletter_subscribe() {
        check_ajax_referer('worprees_nonce', 'nonce');
        
        if (!isset($_POST['email']) || empty($_POST['email'])) {
            wp_send_json_error(array('message' => '44374148 27cc45cc44 2e482f 2744322745cc 27332a'));
        }
        
        $email = sanitize_email($_POST['email']);
        
        if (!is_email($email)) {
            wp_send_json_error(array('message' => '27cc45cc44 45392f31 46cc332a'));
        }
        
        // Store in options or send to newsletter service
        $subscribers = get_option('worprees_newsletter_subscribers', array());
        
        if (!in_array($email, $subscribers)) {
            $subscribers[] = $email;
            update_option('worprees_newsletter_subscribers', $subscribers);
        }
        
        wp_send_json_success(array('message' => '2c2e2f48 2e482f 2827 45484142cc2a 27462c2745 342f'));
    }
}

// AJAX search
if (!function_exists('worprees_shop_ajax_search')) {
    add_action('wp_ajax_worprees_ajax_search', 'worprees_shop_ajax_search');
    add_action('wp_ajax_nopriv_worprees_ajax_search', 'worprees_shop_ajax_search');
    
    function worprees_shop_ajax_search() {
        check_ajax_referer('worprees_nonce', 'nonce');
        
        if (!isset($_POST['query']) || empty($_POST['query'])) {
            wp_send_json_error();
        }
        
        $query = sanitize_text_field($_POST['query']);
        
        $args = array(
            'post_type' => 'any',
            's' => $query,
            'posts_per_page' => 5,
            'post_status' => 'publish',
        );
        
        $search_query = new WP_Query($args);
        
        $results = array();
        
        if ($search_query->have_posts()) {
            while ($search_query->have_posts()) {
                $search_query->the_post();
                
                $result = array(
                    'title' => get_the_title(),
                    'url' => get_permalink(),
                    'type' => get_post_type(),
                );
                
                if (has_post_thumbnail()) {
                    $result['thumbnail'] = get_the_post_thumbnail_url(get_the_ID(), 'worprees-thumbnail');
                }
                
                $results[] = $result;
            }
        }
        
        wp_reset_postdata();
        wp_send_json_success(array('results' => $results));
    }
}

// Load more posts
if (!function_exists('worprees_shop_load_more_posts')) {
    add_action('wp_ajax_worprees_load_more_posts', 'worprees_shop_load_more_posts');
    add_action('wp_ajax_nopriv_worprees_load_more_posts', 'worprees_shop_load_more_posts');
    
    function worprees_shop_load_more_posts() {
        check_ajax_referer('worprees_nonce', 'nonce');
        
        $paged = isset($_POST['paged']) ? intval($_POST['paged']) : 1;
        $posts_per_page = isset($_POST['posts_per_page']) ? intval($_POST['posts_per_page']) : 6;
        
        $args = array(
            'post_type' => 'post',
            'posts_per_page' => $posts_per_page,
            'paged' => $paged,
            'post_status' => 'publish',
        );
        
        $query = new WP_Query($args);
        
        if ($query->have_posts()) {
            ob_start();
            
            while ($query->have_posts()) {
                $query->the_post();
                get_template_part('template-parts/content', 'post');
            }
            
            $html = ob_get_clean();
            wp_send_json_success(array(
                'html' => $html,
                'has_more' => $query->have_posts(),
            ));
        }
        
        wp_reset_postdata();
        wp_send_json_error();
    }
}
