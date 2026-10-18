<?php
/**
 * Worprees Theme AJAX Functions
 * AJAX handlers for quick view, wishlist, compare, cart, newsletter, search, etc.
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
    $product = wc_get_product($product_id);
    
    if (!$product) {
        wp_send_json_error(array(
            'message' => esc_html__('محصول یافت نشد.', 'worprees'),
        ));
    }
    
    ob_start();
    
    // Product image
    echo '<div class="worprees-quick-view-modal woocommerce" id="worpreesQuickViewModal">';
    echo '<button class="worprees-quick-view-close" onclick="worpreesCloseQuickView()"><i class="fas fa-times"></i></button>';
    echo '<div class="worprees-quick-view-content">';
    echo '<div class="worprees-quick-view-image">';
    echo '<a href="' . esc_url(get_permalink($product_id)) . '">';
    echo get_the_post_thumbnail($product_id, 'worprees-medium', array('class' => 'worprees-quick-view-img'));
    echo '</a>';
    echo '</div>';
    
    echo '<div class="worprees-quick-view-info">';
    echo '<h3 class="worprees-quick-view-title"><a href="' . esc_url(get_permalink($product_id)) . '">' . esc_html(get_the_title($product_id)) . '</a></h3>';
    
    // Rating
    if (function_exists('wc_get_rating_html')) {
        echo '<div class="worprees-quick-view-rating">' . wc_get_rating_html($product->get_average_rating()) . '</div>';
    }
    
    // Price
    echo '<div class="worprees-quick-view-price">' . $product->get_price_html() . '</div>';
    
    // Short description
    if ($product->get_short_description()) {
        echo '<div class="worprees-quick-view-description">' . wp_kses_post(wpautop($product->get_short_description())) . '</div>';
    }
    
    // Add to cart
    echo '<div class="worprees-quick-view-actions">';
    if ($product->is_purchasable() && $product->is_in_stock()) {
        echo '<div class="worprees-quick-view-quantity">';
        woocommerce_quantity_input(array(
            'min_value' => 1,
            'max_value' => $product->get_max_purchase_quantity(),
            'step' => $product->get_step(),
        ));
        echo '</div>';
        echo '<button class="worprees-quick-view-add-to-cart button alt" onclick="worpreesAddToCart(' . esc_js($product_id) . ')">' . esc_html__('افزودن به سبد خرید', 'worprees') . '</button>';
    } else {
        echo '<button class="worprees-quick-view-add-to-cart button disabled">' . esc_html__('ناموجود', 'worprees') . '</button>';
    }
    echo '</div>';
    
    // Wishlist and Compare
    echo '<div class="worprees-quick-view-meta">';
    echo '<button class="worprees-quick-view-wishlist" onclick="worpreesAddToWishlist(' . esc_js($product_id) . ')"><i class="far fa-heart"></i> ' . esc_html__('افزودن به علاقه مندی ها', 'worprees') . '</button>';
    echo '<button class="worprees-quick-view-compare" onclick="worpreesAddToCompare(' . esc_js($product_id) . ')"><i class="fas fa-exchange-alt"></i> ' . esc_html__('مقایسه', 'worprees') . '</button>';
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
            'message' => esc_html__('محصول به علاقه مندی ها اضافه شد.', 'worprees'),
            'count' => count($wishlist),
        ));
    } else {
        wp_send_json_error(array(
            'message' => esc_html__('این محصول قبلاً به علاقه مندی ها اضافه شده است.', 'worprees'),
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
            'message' => esc_html__('محصول از علاقه مندی ها حذف شد.', 'worprees'),
            'count' => count($wishlist),
        ));
    } else {
        wp_send_json_error(array(
            'message' => esc_html__('محصول در علاقه مندی ها یافت نشد.', 'worprees'),
        ));
    }
}

/**
 * Get Wishlist AJAX Handler
 */
add_action('wp_ajax_worprees_get_wishlist', 'worprees_get_wishlist');
add_action('wp_ajax_nopriv_worprees_get_wishlist', 'worprees_get_wishlist');
function worprees_get_wishlist() {
    worprees_verify_ajax_nonce();
    
    // Get wishlist from cookies
    $wishlist = isset($_COOKIE['worprees_wishlist']) ? json_decode($_COOKIE['worprees_wishlist'], true) : array();
    
    if (!is_array($wishlist)) {
        $wishlist = array();
    }
    
    $products = array();
    foreach ($wishlist as $product_id) {
        $product = wc_get_product($product_id);
        if ($product) {
            $products[] = array(
                'id' => $product_id,
                'title' => get_the_title($product_id),
                'price' => $product->get_price_html(),
                'image' => get_the_post_thumbnail_url($product_id, 'worprees-thumbnail'),
                'url' => get_permalink($product_id),
            );
        }
    }
    
    ob_start();
    if (!empty($products)) {
        echo '<div class="worprees-wishlist-dropdown">';
        echo '<div class="worprees-wishlist-items">';
        foreach ($products as $product) {
            echo '<div class="worprees-wishlist-item">';
            echo '<a href="' . esc_url($product['url']) . '" class="worprees-wishlist-image">';
            echo '<img src="' . esc_url($product['image']) . '" alt="' . esc_attr($product['title']) . '">';
            echo '</a>';
            echo '<div class="worprees-wishlist-info">';
            echo '<a href="' . esc_url($product['url']) . '" class="worprees-wishlist-title">' . esc_html($product['title']) . '</a>';
            echo '<span class="worprees-wishlist-price">' . $product['price'] . '</span>';
            echo '</div>';
            echo '<button class="worprees-wishlist-remove" onclick="worpreesRemoveFromWishlist(' . esc_js($product['id']) . ')"><i class="fas fa-times"></i></button>';
            echo '</div>';
        }
        echo '</div>';
        echo '<div class="worprees-wishlist-footer">';
        echo '<a href="' . esc_url(wc_get_page_permalink('myaccount')) . '?wishlist=1" class="worprees-wishlist-view-all">' . esc_html__('مشاهده همه', 'worprees') . '</a>';
        echo '</div>';
        echo '</div>';
    } else {
        echo '<div class="worprees-wishlist-dropdown">';
        echo '<div class="worprees-wishlist-empty">' . esc_html__('لیست علاقه مندی ها خالی است.', 'worprees') . '</div>';
        echo '</div>';
    }
    $output = ob_get_clean();
    
    wp_send_json_success(array(
        'html' => $output,
        'count' => count($products),
    ));
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
            'message' => esc_html__('شما می توانید حداکثر 4 محصول را مقایسه کنید.', 'worprees'),
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
 * Get Compare AJAX Handler
 */
add_action('wp_ajax_worprees_get_compare', 'worprees_get_compare');
add_action('wp_ajax_nopriv_worprees_get_compare', 'worprees_get_compare');
function worprees_get_compare() {
    worprees_verify_ajax_nonce();
    
    // Get compare from cookies
    $compare = isset($_COOKIE['worprees_compare']) ? json_decode($_COOKIE['worprees_compare'], true) : array();
    
    if (!is_array($compare)) {
        $compare = array();
    }
    
    $products = array();
    foreach ($compare as $product_id) {
        $product = wc_get_product($product_id);
        if ($product) {
            $products[] = array(
                'id' => $product_id,
                'title' => get_the_title($product_id),
                'image' => get_the_post_thumbnail_url($product_id, 'worprees-thumbnail'),
                'url' => get_permalink($product_id),
            );
        }
    }
    
    ob_start();
    if (!empty($products)) {
        echo '<div class="worprees-compare-dropdown">';
        echo '<div class="worprees-compare-items">';
        foreach ($products as $product) {
            echo '<div class="worprees-compare-item">';
            echo '<a href="' . esc_url($product['url']) . '" class="worprees-compare-image">';
            echo '<img src="' . esc_url($product['image']) . '" alt="' . esc_attr($product['title']) . '">';
            echo '</a>';
            echo '<div class="worprees-compare-info">';
            echo '<a href="' . esc_url($product['url']) . '" class="worprees-compare-title">' . esc_html($product['title']) . '</a>';
            echo '</div>';
            echo '<button class="worprees-compare-remove" onclick="worpreesRemoveFromCompare(' . esc_js($product['id']) . ')"><i class="fas fa-times"></i></button>';
            echo '</div>';
        }
        echo '</div>';
        echo '<div class="worprees-compare-footer">';
        echo '<a href="' . esc_url(wc_get_page_permalink('myaccount')) . '?compare=1" class="worprees-compare-view-all">' . esc_html__('مشاهده همه', 'worprees') . '</a>';
        echo '</div>';
        echo '</div>';
    } else {
        echo '<div class="worprees-compare-dropdown">';
        echo '<div class="worprees-compare-empty">' . esc_html__('لیست مقایسه خالی است.', 'worprees') . '</div>';
        echo '</div>';
    }
    $output = ob_get_clean();
    
    wp_send_json_success(array(
        'html' => $output,
        'count' => count($products),
    ));
}

/**
 * AJAX Add to Cart Handler
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
        $cart_total = WC()->cart->get_cart_total();
        
        ob_start();
        woocommerce_mini_cart();
        $mini_cart = ob_get_clean();
        
        wp_send_json_success(array(
            'message' => esc_html__('محصول به سبد خرید اضافه شد.', 'worprees'),
            'cart_count' => $cart_count,
            'cart_total' => $cart_total,
            'mini_cart' => $mini_cart,
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
    
    // Send welcome email
    $subject = esc_html__('خوش آمدید به خبرنامه ما', 'worprees');
    $message = esc_html__('سلام،', 'worprees') . '\n\n';
    $message .= esc_html__('از ثبت نام شما در خبرنامه ما سپاسگزاریم.', 'worprees') . '\n\n';
    $message .= esc_html__('شما می توانید با کلیک بر روی لینک زیر از خبرنامه انصراف دهید:', 'worprees') . '\n';
    $message .= home_url('/newsletter-unsubscribe?email=' . urlencode($email)) . '\n';
    
    wp_mail($email, $subject, $message);
    
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
    $body = esc_html__('نام:', 'worprees') . ' ' . $name . '\n\n';
    $body .= esc_html__('ایمیل:', 'worprees') . ' ' . $email . '\n\n';
    $body .= esc_html__('پیام:', 'worprees') . '\n' . $message . '\n';
    
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
        echo '<div class="worprees-search-results">';
        foreach ($results as $result) {
            echo '<a href="' . esc_url($result['url']) . '" class="worprees-search-result">';
            if ($result['image']) {
                echo '<div class="worprees-search-result-image">';
                echo '<img src="' . esc_url($result['image']) . '" alt="' . esc_attr($result['title']) . '">';
                echo '</div>';
            }
            echo '<div class="worprees-search-result-info">';
            echo '<span class="worprees-search-result-title">' . esc_html($result['title']) . '</span>';
            echo '<span class="worprees-search-result-type">' . esc_html($result['type']) . '</span>';
            echo '</div>';
            echo '</a>';
        }
        echo '</div>';
    } else {
        echo '<div class="worprees-search-results">';
        echo '<div class="worprees-search-no-results">' . esc_html__('موردی یافت نشد.', 'worprees') . '</div>';
        echo '</div>';
    }
    $output = ob_get_clean();
    
    wp_send_json_success(array(
        'html' => $output,
        'count' => count($results),
    ));
}

/**
 * Load More Posts AJAX Handler
 */
add_action('wp_ajax_worprees_load_more_posts', 'worprees_load_more_posts');
add_action('wp_ajax_nopriv_worprees_load_more_posts', 'worprees_load_more_posts');
function worprees_load_more_posts() {
    worprees_verify_ajax_nonce();
    
    $page = isset($_POST['page']) && is_numeric($_POST['page']) ? intval($_POST['page']) : 1;
    $posts_per_page = isset($_POST['posts_per_page']) && is_numeric($_POST['posts_per_page']) ? intval($_POST['posts_per_page']) : 4;
    $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '';
    
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => $posts_per_page,
        'paged' => $page,
        'post_status' => 'publish',
    );
    
    if ($category) {
        $args['category_name'] = $category;
    }
    
    $query = new WP_Query($args);
    
    ob_start();
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            
            echo '<article class="worprees-post animate__animated animate__fadeInUp">';
            echo '<div class="worprees-post-thumbnail">';
            echo '<a href="' . esc_url(get_permalink()) . '">';
            echo get_the_post_thumbnail(get_the_ID(), 'worprees-medium');
            echo '</a>';
            echo '</div>';
            echo '<div class="worprees-post-content">';
            echo '<h3 class="worprees-post-title"><a href="' . esc_url(get_permalink()) . '">' . esc_html(get_the_title()) . '</a></h3>';
            echo '<div class="worprees-post-meta">';
            echo '<span class="worprees-post-date"><i class="far fa-calendar-alt"></i> ' . esc_html(get_the_date()) . '</span>';
            echo '<span class="worprees-post-author"><i class="far fa-user"></i> ' . esc_html(get_the_author()) . '</span>';
            echo '<span class="worprees-post-comments"><i class="far fa-comment"></i> ' . esc_html(get_comments_number()) . '</span>';
            echo '</div>';
            echo '<div class="worprees-post-excerpt">' . wp_kses_post(worprees_excerpt(15)) . '</div>';
            echo '<a href="' . esc_url(get_permalink()) . '" class="worprees-post-read-more">' . esc_html__('ادامه مطلب', 'worprees') . '</a>';
            echo '</div>';
            echo '</article>';
        }
        wp_reset_postdata();
    }
    $output = ob_get_clean();
    
    wp_send_json_success(array(
        'html' => $output,
        'has_more' => $query->max_num_pages > $page,
    ));
}

/**
 * Load More Products AJAX Handler
 */
add_action('wp_ajax_worprees_load_more_products', 'worprees_load_more_products');
add_action('wp_ajax_nopriv_worprees_load_more_products', 'worprees_load_more_products');
function worprees_load_more_products() {
    worprees_verify_ajax_nonce();
    
    $page = isset($_POST['page']) && is_numeric($_POST['page']) ? intval($_POST['page']) : 1;
    $posts_per_page = isset($_POST['posts_per_page']) && is_numeric($_POST['posts_per_page']) ? intval($_POST['posts_per_page']) : 8;
    $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '';
    $orderby = isset($_POST['orderby']) ? sanitize_text_field($_POST['orderby']) : 'date';
    $order = isset($_POST['order']) ? sanitize_text_field($_POST['order']) : 'DESC';
    
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => $posts_per_page,
        'paged' => $page,
        'post_status' => 'publish',
        'orderby' => $orderby,
        'order' => $order,
    );
    
    if ($category) {
        $args['product_cat'] = $category;
    }
    
    $query = new WP_Query($args);
    
    ob_start();
    if ($query->have_posts()) {
        echo '<div class="worprees-products-grid">';
        while ($query->have_posts()) {
            $query->the_post();
            
            global $product;
            
            echo '<div class="worprees-product animate__animated animate__fadeInUp">';
            echo '<div class="worprees-product-thumbnail">';
            echo '<a href="' . esc_url(get_permalink()) . '">';
            echo get_the_post_thumbnail(get_the_ID(), 'worprees-product');
            
            // Product badges
            if ($product->is_on_sale()) {
                echo '<span class="worprees-product-badge worprees-product-badge-sale">' . esc_html__('فروش ویژه', 'worprees') . '</span>';
            }
            if ($product->is_featured()) {
                echo '<span class="worprees-product-badge worprees-product-badge-featured">' . esc_html__('ویژه', 'worprees') . '</span>';
            }
            if (!$product->is_in_stock()) {
                echo '<span class="worprees-product-badge worprees-product-badge-out-of-stock">' . esc_html__('ناموجود', 'worprees') . '</span>';
            }
            
            echo '</a>';
            echo '<div class="worprees-product-actions">';
            echo '<button class="worprees-product-quick-view" onclick="worpreesQuickView(' . esc_js(get_the_ID()) . ')"><i class="fas fa-search"></i></button>';
            echo '<button class="worprees-product-wishlist" onclick="worpreesAddToWishlist(' . esc_js(get_the_ID()) . ')"><i class="far fa-heart"></i></button>';
            echo '<button class="worprees-product-compare" onclick="worpreesAddToCompare(' . esc_js(get_the_ID()) . ')"><i class="fas fa-exchange-alt"></i></button>';
            echo '</div>';
            echo '</div>';
            
            echo '<div class="worprees-product-info">';
            echo '<h3 class="worprees-product-title"><a href="' . esc_url(get_permalink()) . '">' . esc_html(get_the_title()) . '</a></h3>';
            echo '<div class="worprees-product-price">' . $product->get_price_html() . '</div>';
            
            // Rating
            if (function_exists('wc_get_rating_html')) {
                echo '<div class="worprees-product-rating">' . wc_get_rating_html($product->get_average_rating()) . '</div>';
            }
            
            echo '<div class="worprees-product-add-to-cart">';
            if ($product->is_purchasable() && $product->is_in_stock()) {
                echo '<button class="worprees-product-add-to-cart-button button" onclick="worpreesAddToCart(' . esc_js(get_the_ID()) . ')">' . esc_html__('افزودن به سبد خرید', 'worprees') . '</button>';
            } else {
                echo '<button class="worprees-product-add-to-cart-button button disabled">' . esc_html__('ناموجود', 'worprees') . '</button>';
            }
            echo '</div>';
            echo '</div>';
            echo '</div>';
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

/**
 * Copy Link AJAX Handler
 */
add_action('wp_ajax_worprees_copy_link', 'worprees_copy_link');
add_action('wp_ajax_nopriv_worprees_copy_link', 'worprees_copy_link');
function worprees_copy_link() {
    worprees_verify_ajax_nonce();
    
    if (!isset($_POST['url']) || empty($_POST['url'])) {
        wp_send_json_error(array(
            'message' => esc_html__('لینک معتبر نیست.', 'worprees'),
        ));
    }
    
    $url = esc_url($_POST['url']);
    
    // Copy to clipboard using JavaScript
    wp_send_json_success(array(
        'message' => esc_html__('لینک در کلیپ بورد کپی شد.', 'worprees'),
        'url' => $url,
    ));
}

/**
 * Notification AJAX Handler
 */
add_action('wp_ajax_worprees_notification', 'worprees_notification');
add_action('wp_ajax_nopriv_worprees_notification', 'worprees_notification');
function worprees_notification() {
    worprees_verify_ajax_nonce();
    
    $type = isset($_POST['type']) ? sanitize_text_field($_POST['type']) : 'info';
    $message = isset($_POST['message']) ? sanitize_text_field($_POST['message']) : '';
    
    if (empty($message)) {
        wp_send_json_error(array(
            'message' => esc_html__('پیام خالی است.', 'worprees'),
        ));
    }
    
    wp_send_json_success(array(
        'type' => $type,
        'message' => $message,
    ));
}

/**
 * Modal AJAX Handler
 */
add_action('wp_ajax_worprees_modal', 'worprees_modal');
add_action('wp_ajax_nopriv_worprees_modal', 'worprees_modal');
function worprees_modal() {
    worprees_verify_ajax_nonce();
    
    $title = isset($_POST['title']) ? sanitize_text_field($_POST['title']) : '';
    $content = isset($_POST['content']) ? wp_kses_post($_POST['content']) : '';
    $size = isset($_POST['size']) ? sanitize_text_field($_POST['size']) : 'medium';
    
    ob_start();
    echo '<div class="worprees-modal worprees-modal-' . esc_attr($size) . '" id="worpreesModal">';
    echo '<button class="worprees-modal-close" onclick="worpreesCloseModal()"><i class="fas fa-times"></i></button>';
    if ($title) {
        echo '<h3 class="worprees-modal-title">' . esc_html($title) . '</h3>';
    }
    echo '<div class="worprees-modal-content">' . $content . '</div>';
    echo '</div>';
    $output = ob_get_clean();
    
    wp_send_json_success(array(
        'html' => $output,
    ));
}
