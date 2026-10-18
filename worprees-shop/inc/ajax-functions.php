<?php
/**
 * Worprees Shop AJAX Functions
 * AJAX handlers for theme functionality
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Verify AJAX nonce
 */
function worprees_verify_ajax_nonce() {
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'worprees_nonce')) {
        wp_send_json_error(array(
            'message' => esc_html__('خطا در اعتبارسنجی امنیت. لطفا صفحه را مجددا بارگذاری کنید.', 'worprees'),
        ));
    }
}

/**
 * Quick View AJAX Handler
 */
add_action('wp_ajax_worprees_quick_view', 'worprees_quick_view');
add_action('wp_ajax_nopriv_worprees_quick_view', 'worprees_quick_view');
function worprees_quick_view() {
    worprees_verify_ajax_nonce();
    
    if (!isset($_POST['product_id']) || !is_numeric($_POST['product_id'])) {
        wp_send_json_error(array(
            'message' => esc_html__('شناسه محصول معتبر نیست.', 'worprees'),
        ));
    }
    
    $product_id = intval($_POST['product_id']);
    
    if (!class_exists('WooCommerce')) {
        wp_send_json_error(array(
            'message' => esc_html__('WooCommerce فعال نیست.', 'worprees'),
        ));
    }
    
    $product = wc_get_product($product_id);
    
    if (!$product) {
        wp_send_json_error(array(
            'message' => esc_html__('محصول یافت نشد.', 'worprees'),
        ));
    }
    
    ob_start();
    
    echo '<div class="quick-view-content">';
    echo '<div class="quick-view-row">';
    
    // Product image
    echo '<div class="quick-view-image">';
    if (has_post_thumbnail($product_id)) {
        echo get_the_post_thumbnail($product_id, 'worprees-medium');
    } else {
        echo '<img src="' . esc_url(wc_placeholder_img_src()) . '" alt="' . esc_attr(get_the_title($product_id)) . '">';
    }
    echo '</div>';
    
    // Product info
    echo '<div class="quick-view-info">';
    echo '<h3 class="quick-view-title">' . esc_html(get_the_title($product_id)) . '</h3>';
    
    // Rating
    if (function_exists('wc_get_rating_html')) {
        echo '<div class="quick-view-rating">' . wc_get_rating_html($product->get_average_rating()) . '</div>';
    }
    
    // Price
    echo '<div class="quick-view-price">' . $product->get_price_html() . '</div>';
    
    // Short description
    if ($product->get_short_description()) {
        echo '<div class="quick-view-description">' . wp_kses_post(wpautop($product->get_short_description())) . '</div>';
    }
    
    // Add to cart
    echo '<div class="quick-view-actions">';
    if ($product->is_purchasable() && $product->is_in_stock()) {
        echo '<button class="quick-view-add-to-cart btn btn-primary" data-product-id="' . esc_attr($product_id) . '">';
        echo '<i class="fas fa-shopping-cart"></i> افزودن به سبد خرید';
        echo '</button>';
    } else {
        echo '<button class="quick-view-add-to-cart btn btn-primary disabled">ناموجود</button>';
    }
    echo '</div>';
    
    // Wishlist and Compare
    echo '<div class="quick-view-meta">';
    echo '<button class="quick-view-wishlist" data-product-id="' . esc_attr($product_id) . '">';
    echo '<i class="far fa-heart"></i> افزودن به علاقه‌مندها';
    echo '</button>';
    echo '<button class="quick-view-compare" data-product-id="' . esc_attr($product_id) . '">';
    echo '<i class="fas fa-exchange-alt"></i> مقایسه';
    echo '</button>';
    echo '</div>';
    
    echo '</div>';
    echo '</div>';
    echo '</div>';
    
    $output = ob_get_clean();
    
    wp_send_json_success(array(
        'html' => $output,
    ));
}

/**
 * Add to Wishlist AJAX Handler
 */
add_action('wp_ajax_worprees_add_to_wishlist', 'worprees_add_to_wishlist');
add_action('wp_ajax_nopriv_worprees_add_to_wishlist', 'worprees_add_to_wishlist');
function worprees_add_to_wishlist() {
    worprees_verify_ajax_nonce();
    
    if (!isset($_POST['product_id']) || !is_numeric($_POST['product_id'])) {
        wp_send_json_error(array(
            'message' => esc_html__('شناسه محصول معتبر نیست.', 'worprees'),
        ));
    }
    
    $product_id = intval($_POST['product_id']);
    
    // Get wishlist from cookies
    $wishlist = isset($_COOKIE['worprees_wishlist']) ? json_decode($_COOKIE['worprees_wishlist'], true) : array();
    
    if (!is_array($wishlist)) {
        $wishlist = array();
    }
    
    // Add product to wishlist
    if (!in_array($product_id, $wishlist)) {
        $wishlist[] = $product_id;
        setcookie('worprees_wishlist', json_encode($wishlist), time() + 30 * DAY_IN_SECONDS, '/');
        
        wp_send_json_success(array(
            'message' => esc_html__('محصول به علاقه‌مندها اضافه شد.', 'worprees'),
            'count' => count($wishlist),
        ));
    } else {
        wp_send_json_error(array(
            'message' => esc_html__('این محصول قبلاً به علاقه‌مندها اضافه شده است.', 'worprees'),
        ));
    }
}

/**
 * Remove from Wishlist AJAX Handler
 */
add_action('wp_ajax_worprees_remove_from_wishlist', 'worprees_remove_from_wishlist');
add_action('wp_ajax_nopriv_worprees_remove_from_wishlist', 'worprees_remove_from_wishlist');
function worprees_remove_from_wishlist() {
    worprees_verify_ajax_nonce();
    
    if (!isset($_POST['product_id']) || !is_numeric($_POST['product_id'])) {
        wp_send_json_error(array(
            'message' => esc_html__('شناسه محصول معتبر نیست.', 'worprees'),
        ));
    }
    
    $product_id = intval($_POST['product_id']);
    
    // Get wishlist from cookies
    $wishlist = isset($_COOKIE['worprees_wishlist']) ? json_decode($_COOKIE['worprees_wishlist'], true) : array();
    
    if (!is_array($wishlist)) {
        $wishlist = array();
    }
    
    // Remove product from wishlist
    $index = array_search($product_id, $wishlist);
    if ($index !== false) {
        unset($wishlist[$index]);
        setcookie('worprees_wishlist', json_encode(array_values($wishlist)), time() + 30 * DAY_IN_SECONDS, '/');
        
        wp_send_json_success(array(
            'message' => esc_html__('محصول از علاقه‌مندها حذف شد.', 'worprees'),
            'count' => count($wishlist),
        ));
    } else {
        wp_send_json_error(array(
            'message' => esc_html__('محصول در علاقه‌مندها یافت نشد.', 'worprees'),
        ));
    }
}

/**
 * Add to Compare AJAX Handler
 */
add_action('wp_ajax_worprees_add_to_compare', 'worprees_add_to_compare');
add_action('wp_ajax_nopriv_worprees_add_to_compare', 'worprees_add_to_compare');
function worprees_add_to_compare() {
    worprees_verify_ajax_nonce();
    
    if (!isset($_POST['product_id']) || !is_numeric($_POST['product_id'])) {
        wp_send_json_error(array(
            'message' => esc_html__('شناسه محصول معتبر نیست.', 'worprees'),
        ));
    }
    
    $product_id = intval($_POST['product_id']);
    
    // Get compare from cookies
    $compare = isset($_COOKIE['worprees_compare']) ? json_decode($_COOKIE['worprees_compare'], true) : array();
    
    if (!is_array($compare)) {
        $compare = array();
    }
    
    // Limit to 4 products
    if (count($compare) >= 4) {
        wp_send_json_error(array(
            'message' => esc_html__('شما می‌توانید حداکثر 4 محصول را مقایسه کنید.', 'worprees'),
        ));
    }
    
    // Add product to compare
    if (!in_array($product_id, $compare)) {
        $compare[] = $product_id;
        setcookie('worprees_compare', json_encode($compare), time() + 30 * DAY_IN_SECONDS, '/');
        
        wp_send_json_success(array(
            'message' => esc_html__('محصول به لیست مقایسه اضافه شد.', 'worprees'),
            'count' => count($compare),
        ));
    } else {
        wp_send_json_error(array(
            'message' => esc_html__('این محصول قبلاً به لیست مقایسه اضافه شده است.', 'worprees'),
        ));
    }
}

/**
 * Remove from Compare AJAX Handler
 */
add_action('wp_ajax_worprees_remove_from_compare', 'worprees_remove_from_compare');
add_action('wp_ajax_nopriv_worprees_remove_from_compare', 'worprees_remove_from_compare');
function worprees_remove_from_compare() {
    worprees_verify_ajax_nonce();
    
    if (!isset($_POST['product_id']) || !is_numeric($_POST['product_id'])) {
        wp_send_json_error(array(
            'message' => esc_html__('شناسه محصول معتبر نیست.', 'worprees'),
        ));
    }
    
    $product_id = intval($_POST['product_id']);
    
    // Get compare from cookies
    $compare = isset($_COOKIE['worprees_compare']) ? json_decode($_COOKIE['worprees_compare'], true) : array();
    
    if (!is_array($compare)) {
        $compare = array();
    }
    
    // Remove product from compare
    $index = array_search($product_id, $compare);
    if ($index !== false) {
        unset($compare[$index]);
        setcookie('worprees_compare', json_encode(array_values($compare)), time() + 30 * DAY_IN_SECONDS, '/');
        
        wp_send_json_success(array(
            'message' => esc_html__('محصول از لیست مقایسه حذف شد.', 'worprees'),
            'count' => count($compare),
        ));
    } else {
        wp_send_json_error(array(
            'message' => esc_html__('محصول در لیست مقایسه یافت نشد.', 'worprees'),
        ));
    }
}

/**
 * Add to Cart AJAX Handler
 */
add_action('wp_ajax_worprees_add_to_cart', 'worprees_add_to_cart');
add_action('wp_ajax_nopriv_worprees_add_to_cart', 'worprees_add_to_cart');
function worprees_add_to_cart() {
    worprees_verify_ajax_nonce();
    
    if (!isset($_POST['product_id']) || !is_numeric($_POST['product_id'])) {
        wp_send_json_error(array(
            'message' => esc_html__('شناسه محصول معتبر نیست.', 'worprees'),
        ));
    }
    
    if (!class_exists('WooCommerce')) {
        wp_send_json_error(array(
            'message' => esc_html__('WooCommerce فعال نیست.', 'worprees'),
        ));
    }
    
    $product_id = intval($_POST['product_id']);
    $quantity = isset($_POST['quantity']) && is_numeric($_POST['quantity']) ? intval($_POST['quantity']) : 1;
    $variation_id = isset($_POST['variation_id']) && is_numeric($_POST['variation_id']) ? intval($_POST['variation_id']) : 0;
    $variation = isset($_POST['variation']) ? $_POST['variation'] : array();
    
    try {
        if ($variation_id > 0) {
            WC()->cart->add_to_cart($product_id, $quantity, $variation_id, $variation);
        } else {
            WC()->cart->add_to_cart($product_id, $quantity);
        }
        
        $cart_count = WC()->cart->get_cart_contents_count();
        
        wp_send_json_success(array(
            'message' => esc_html__('محصول به سبد خرید اضافه شد.', 'worprees'),
            'cart_count' => $cart_count,
        ));
    } catch (Exception $e) {
        wp_send_json_error(array(
            'message' => $e->getMessage(),
        ));
    }
}

/**
 * Newsletter Subscribe AJAX Handler
 */
add_action('wp_ajax_worprees_newsletter_subscribe', 'worprees_newsletter_subscribe');
add_action('wp_ajax_nopriv_worprees_newsletter_subscribe', 'worprees_newsletter_subscribe');
function worprees_newsletter_subscribe() {
    worprees_verify_ajax_nonce();
    
    if (!isset($_POST['email']) || empty($_POST['email'])) {
        wp_send_json_error(array(
            'message' => esc_html__('لطفا آدرس ایمیل خود را وارد کنید.', 'worprees'),
        ));
    }
    
    $email = sanitize_email($_POST['email']);
    
    if (!is_email($email)) {
        wp_send_json_error(array(
            'message' => esc_html__('آدرس ایمیل معتبر نیست.', 'worprees'),
        ));
    }
    
    // Check if already subscribed
    $subscribers = get_option('worprees_newsletter_subscribers', array());
    if (in_array($email, $subscribers)) {
        wp_send_json_error(array(
            'message' => esc_html__('این ایمیل قبلاً ثبت نام شده است.', 'worprees'),
        ));
    }
    
    // Add to subscribers
    $subscribers[] = $email;
    update_option('worprees_newsletter_subscribers', $subscribers);
    
    wp_send_json_success(array(
        'message' => esc_html__('با موفقیت ثبت نام شدید.', 'worprees'),
    ));
}

/**
 * Contact Form AJAX Handler
 */
add_action('wp_ajax_worprees_contact_form', 'worprees_contact_form');
add_action('wp_ajax_nopriv_worprees_contact_form', 'worprees_contact_form');
function worprees_contact_form() {
    worprees_verify_ajax_nonce();
    
    if (!isset($_POST['name']) || empty($_POST['name'])) {
        wp_send_json_error(array(
            'message' => esc_html__('لطفا نام خود را وارد کنید.', 'worprees'),
        ));
    }
    
    if (!isset($_POST['email']) || empty($_POST['email'])) {
        wp_send_json_error(array(
            'message' => esc_html__('لطفا آدرس ایمیل خود را وارد کنید.', 'worprees'),
        ));
    }
    
    if (!isset($_POST['message']) || empty($_POST['message'])) {
        wp_send_json_error(array(
            'message' => esc_html__('لطفا پیام خود را وارد کنید.', 'worprees'),
        ));
    }
    
    $name = sanitize_text_field($_POST['name']);
    $email = sanitize_email($_POST['email']);
    $message = sanitize_textarea_field($_POST['message']);
    
    if (!is_email($email)) {
        wp_send_json_error(array(
            'message' => esc_html__('آدرس ایمیل معتبر نیست.', 'worprees'),
        ));
    }
    
    $admin_email = get_option('admin_email');
    $subject = esc_html__('پیام جدید از فرم تماس', 'worprees');
    $body = esc_html__('نام:', 'worprees') . ' ' . $name . "\n\n";
    $body .= esc_html__('ایمیل:', 'worprees') . ' ' . $email . "\n\n";
    $body .= esc_html__('پیام:', 'worprees') . "\n" . $message . "\n";
    
    $sent = wp_mail($admin_email, $subject, $body);
    
    if ($sent) {
        wp_send_json_success(array(
            'message' => esc_html__('پیام شما با موفقیت ارسال شد.', 'worprees'),
        ));
    } else {
        wp_send_json_error(array(
            'message' => esc_html__('خطا در ارسال پیام. لطفا مجددا تلاش کنید.', 'worprees'),
        ));
    }
}

/**
 * AJAX Search Handler
 */
add_action('wp_ajax_worprees_search', 'worprees_search');
add_action('wp_ajax_nopriv_worprees_search', 'worprees_search');
function worprees_search() {
    worprees_verify_ajax_nonce();
    
    if (!isset($_POST['query']) || empty($_POST['query'])) {
        wp_send_json_error(array(
            'message' => esc_html__('لطفا کلمه جستجو را وارد کنید.', 'worprees'),
        ));
    }
    
    $query = sanitize_text_field($_POST['query']);
    $limit = isset($_POST['limit']) && is_numeric($_POST['limit']) ? intval($_POST['limit']) : 5;
    
    $args = array(
        'post_type' => array('post', 'product'),
        'posts_per_page' => $limit,
        's' => $query,
        'post_status' => 'publish',
    );
    
    $search_query = new WP_Query($args);
    
    $results = array();
    if ($search_query->have_posts()) {
        while ($search_query->have_posts()) {
            $search_query->the_post();
            
            $post_type = get_post_type();
            $post_type_object = get_post_type_object($post_type);
            
            $results[] = array(
                'id' => get_the_ID(),
                'title' => get_the_title(),
                'url' => get_permalink(),
                'type' => $post_type_object ? $post_type_object->labels->singular_name : $post_type,
                'image' => get_the_post_thumbnail_url(get_the_ID(), 'worprees-thumbnail'),
            );
        }
        wp_reset_postdata();
    }
    
    ob_start();
    if (!empty($results)) {
        echo '<div class="search-results-dropdown">';
        foreach ($results as $result) {
            echo '<a href="' . esc_url($result['url']) . '" class="search-result">';
            if ($result['image']) {
                echo '<div class="search-result-image">';
                echo '<img src="' . esc_url($result['image']) . '" alt="' . esc_attr($result['title']) . '">';
                echo '</div>';
            }
            echo '<div class="search-result-info">';
            echo '<span class="search-result-title">' . esc_html($result['title']) . '</span>';
            echo '<span class="search-result-type">' . esc_html($result['type']) . '</span>';
            echo '</div>';
            echo '</a>';
        }
        echo '</div>';
    } else {
        echo '<div class="search-results-dropdown">';
        echo '<div class="search-no-results">موردی یافت نشد</div>';
        echo '</div>';
    }
    $output = ob_get_clean();
    
    wp_send_json_success(array(
        'html' => $output,
        'count' => count($results),
    ));
}

/**
 * Load More Products Handler
 */
add_action('wp_ajax_worprees_load_more_products', 'worprees_load_more_products');
add_action('wp_ajax_nopriv_worprees_load_more_products', 'worprees_load_more_products');
function worprees_load_more_products() {
    worprees_verify_ajax_nonce();
    
    $page = isset($_POST['page']) && is_numeric($_POST['page']) ? intval($_POST['page']) : 1;
    $posts_per_page = isset($_POST['posts_per_page']) && is_numeric($_POST['posts_per_page']) ? intval($_POST['posts_per_page']) : 8;
    
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => $posts_per_page,
        'paged' => $page,
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC',
    );
    
    $query = new WP_Query($args);
    
    ob_start();
    if ($query->have_posts()) {
        echo '<div class="products-grid">';
        while ($query->have_posts()) {
            $query->the_post();
            
            global $product;
            
            if (!$product) {
                $product = wc_get_product(get_the_ID());
            }
            
            if ($product && $product->is_visible()) {
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
        }
        wp_reset_postdata();
        echo '</div>';
    }
    $output = ob_get_clean();
    
    wp_send_json_success(array(
        'html' => $output,
        'has_more' => $query->max_num_pages > $page,
    ));
}
