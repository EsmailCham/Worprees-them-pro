<?php
/**
 * Worprees Shop Header Template
 */

if (!defined('ABSPATH')) {
    exit;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>

    <!-- Preloader - Only show on front-end, not admin or login pages -->
    <?php if (!is_admin() && !function_exists('wp_login_view')) : ?>
    <div class="site-preloader">
        <div class="preloader-content">
            <div class="preloader-spinner"></div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Site Header -->
    <header class="site-header">
        
        <!-- Top Bar -->
        <?php if (is_active_sidebar('topbar')) : ?>
        <div class="header-topbar">
            <div class="container">
                <div class="topbar-left">
                    <?php dynamic_sidebar('topbar'); ?>
                </div>
                <div class="topbar-right">
                    <!-- Social Links -->
                    <div class="topbar-social">
                        <a href="#" title="تلگرام"><i class="fab fa-telegram-plane"></i></a>
                        <a href="#" title="اینستاگرام"><i class="fab fa-instagram"></i></a>
                        <a href="#" title="واتساپ"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Main Header -->
        <div class="header-main">
            <div class="container">
                <div class="header-row">
                    
                    <!-- Site Branding -->
                    <div class="site-branding">
                        <?php
                        if (has_custom_logo()) {
                            the_custom_logo();
                        } else {
                            echo '<h1 class="site-title">';
                            echo '<a href="' . esc_url(home_url('/')) . '" rel="home">';
                            bloginfo('name');
                            echo '</a>';
                            echo '</h1>';
                            
                            $description = get_bloginfo('description', 'display');
                            if ($description || is_customize_preview()) {
                                echo '<p class="site-description">' . esc_html($description) . '</p>';
                            }
                        }
                        ?>
                    </div>

                    <!-- Main Navigation -->
                    <nav class="main-navigation">
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'primary',
                            'container' => false,
                            'menu_class' => 'nav-menu',
                            'fallback_cb' => 'worprees_shop_primary_menu_fallback',
                            'depth' => 3
                        ));
                        ?>
                    </nav>

                    <!-- Mobile Menu Button -->
                    <button id="mobile-menu-button" class="mobile-menu-button">
                        <i class="fas fa-bars"></i>
                        <span class="screen-reader-text">منو</span>
                    </button>

                    <!-- Header Actions -->
                    <div class="header-actions">
                        
                        <!-- Search Button -->
                        <button id="search-button" class="search-button">
                            <i class="fas fa-search"></i>
                            <span class="screen-reader-text">جستجو</span>
                        </button>

                        <!-- Search Form -->
                        <div class="search-form-container">
                            <form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
                                <input type="search" class="search-field" placeholder="جستجوی محصولات..." value="<?php echo get_search_query(); ?>" name="s" />
                                <button type="submit" class="search-submit">
                                    <i class="fas fa-search"></i>
                                    <span class="screen-reader-text">جستجو</span>
                                </button>
                            </form>
                            <div class="search-results"></div>
                        </div>

                        <!-- Wishlist -->
                        <?php if (class_exists('WooCommerce')) : ?>
                        <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>?wishlist=1" class="header-action wishlist-action">
                            <i class="far fa-heart"></i>
                            <span class="wishlist-count">0</span>
                            <span class="action-label">علاقه‌مندی‌ها</span>
                        </a>
                        <?php endif; ?>

                        <!-- Compare -->
                        <?php if (class_exists('WooCommerce')) : ?>
                        <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>?compare=1" class="header-action compare-action">
                            <i class="fas fa-exchange-alt"></i>
                            <span class="compare-count">0</span>
                            <span class="action-label">مقایسه</span>
                        </a>
                        <?php endif; ?>

                        <!-- Cart -->
                        <?php if (class_exists('WooCommerce')) : ?>
                        <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="header-action cart-action">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
                            <span class="action-label">سبد خرید</span>
                        </a>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </div>

    </header>

    <!-- Quick View Modal -->
    <div id="quick-view-modal" class="modal">
        <div class="modal-content">
            <button class="modal-close">
                <i class="fas fa-times"></i>
                <span class="screen-reader-text">بستن</span>
            </button>
            <div class="modal-body"></div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="site-content">
