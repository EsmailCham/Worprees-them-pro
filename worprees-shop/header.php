<?php
/**
 * The header for our theme
 * 
 * @package Worprees_Shop
 */

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
    
    <!-- Favicon -->
    <?php if (has_site_icon()) : ?>
        <link rel="icon" href="<?php echo esc_url(wp_get_attachment_image_url(get_site_icon_id(), 'full')); ?>" sizes="32x32" />
    <?php endif; ?>
    
    <!-- Meta Tags -->
    <meta name="description" content="<?php bloginfo('description'); ?>">
    <meta name="author" content="<?php bloginfo('name'); ?>">
    
    <!-- Open Graph -->
    <meta property="og:site_name" content="<?php bloginfo('name'); ?>" />
    <meta property="og:title" content="<?php bloginfo('name'); ?> - <?php bloginfo('description'); ?>" />
    <meta property="og:description" content="<?php bloginfo('description'); ?>" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="<?php echo esc_url(home_url('/')); ?>" />
    
    <?php if (is_single() && has_post_thumbnail()) : ?>
        <meta property="og:image" content="<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'large')); ?>" />
    <?php endif; ?>
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php bloginfo('name'); ?> - <?php bloginfo('description'); ?>" />
    <meta name="twitter:description" content="<?php bloginfo('description'); ?>" />
    <meta name="twitter:url" content="<?php echo esc_url(home_url('/')); ?>" />
    
    <?php if (is_single() && has_post_thumbnail()) : ?>
        <meta name="twitter:image" content="<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'large')); ?>" />
    <?php endif; ?>
    
    <!-- Canonical URL -->
    <link rel="canonical" href="<?php echo esc_url(get_canonical_url()); ?>" />
    
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>
    
    <a class="skip-link" href="#main"><?php esc_attr_e('رفتن به محتوا', 'worprees-shop'); ?></a>
    
    <div id="page" class="site">
        <header id="masthead" class="site-header header">
            <!-- Header Top Bar -->
            <div class="header-top">
                <div class="container">
                    <div class="header-top-left">
                        <!-- Social Links -->
                        <div class="social-links">
                            <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                            <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                            <a href="#" aria-label="Telegram"><i class="fab fa-telegram"></i></a>
                        </div>
                    </div>
                    <div class="header-top-right">
                        <!-- Contact Info -->
                        <span class="contact-info"><i class="fas fa-phone"></i> ۰۲۱-۱۲۳۴۵۶۷۸</span>
                        <span class="contact-info"><i class="fas fa-envelope"></i> info@example.com</span>
                    </div>
                </div>
            </div>
            
            <!-- Header Main -->
            <div class="header-main">
                <div class="container">
                    <!-- Logo -->
                    <div class="site-branding">
                        <?php
                        if (has_custom_logo()) :
                            the_custom_logo();
                        else :
                            echo '<h1 class="site-title logo">';
                            echo '<a href="' . esc_url(home_url('/')) . '" rel="home">' . esc_html(get_bloginfo('name')) . '</a>';
                            echo '</h1>';
                        endif;
                        
                        if (get_bloginfo('description')) :
                            echo '<p class="site-description">' . esc_html(get_bloginfo('description')) . '</p>';
                        endif;
                        ?>
                    </div>
                    
                    <!-- Navigation Menu -->
                    <nav id="site-navigation" class="main-navigation">
                        <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
                            <i class="fas fa-bars"></i>
                            <span><?php esc_html_e('منو', 'worprees-shop'); ?></span>
                        </button>
                        
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'primary',
                            'menu_id' => 'primary-menu',
                            'menu_class' => 'nav-menu',
                            'container' => false,
                            'fallback_cb' => 'worprees_shop_primary_menu_fallback',
                            'depth' => 3,
                        ));
                        ?>
                    </nav>
                    
                    <!-- Header Actions -->
                    <div class="header-actions">
                        <!-- Search Button -->
                        <button class="header-action-btn search-btn" aria-label="<?php esc_attr_e('جستجو', 'worprees-shop'); ?>">
                            <i class="fas fa-search"></i>
                        </button>
                        
                        <!-- Cart Button -->
                        <?php if (class_exists('WooCommerce')) : ?>
                            <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="header-action-btn cart-btn" aria-label="<?php esc_attr_e('سبد خرید', 'worprees-shop'); ?>">
                                <i class="fas fa-shopping-cart"></i>
                                <?php if (WC()->cart->get_cart_contents_count() > 0) : ?>
                                    <span class="cart-count"><?php echo esc_html(WC()->cart->get_cart_contents_count()); ?></span>
                                <?php endif; ?>
                            </a>
                        <?php endif; ?>
                        
                        <!-- Mobile Menu Button -->
                        <button class="header-action-btn mobile-menu-btn" aria-label="<?php esc_attr_e('منوی موبایل', 'worprees-shop'); ?>">
                            <i class="fas fa-bars"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Mobile Menu -->
            <div class="mobile-menu-overlay" id="worpreesMobileMenu">
                <div class="mobile-menu-header">
                    <button class="mobile-menu-close" aria-label="<?php esc_attr_e('بستن منو', 'worprees-shop'); ?>">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div class="mobile-menu-content">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'mobile',
                        'menu_id' => 'mobile-menu',
                        'menu_class' => 'mobile-nav-menu',
                        'container' => false,
                        'fallback_cb' => 'worprees_shop_mobile_menu_fallback',
                        'depth' => 3,
                    ));
                    ?>
                </div>
            </div>
            
            <!-- Search Form Overlay -->
            <div class="search-overlay" id="worpreesSearchOverlay">
                <div class="search-overlay-content">
                    <button class="search-overlay-close" aria-label="<?php esc_attr_e('بستن جستجو', 'worprees-shop'); ?>">
                        <i class="fas fa-times"></i>
                    </button>
                    
                    <form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
                        <label for="search-input" class="screen-reader-text"><?php esc_html_e('جستجو برای:', 'worprees-shop'); ?></label>
                        <input type="search" id="search-input" class="search-field" placeholder="<?php esc_attr_e('جستجو...', 'worprees-shop'); ?>" value="<?php echo get_search_query(); ?>" name="s">
                        <button type="submit" class="search-submit">
                            <i class="fas fa-search"></i>
                            <span class="screen-reader-text"><?php esc_html_e('جستجو', 'worprees-shop'); ?></span>
                        </button>
                    </form>
                </div>
            </div>
        </header>
        
        <div id="content" class="site-content">
