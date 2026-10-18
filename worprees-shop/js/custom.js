/**
 * Worprees Shop Custom JavaScript
 * 
 * @package Worprees_Shop
 */

(function($) {
    'use strict';
    
    // Initialize when DOM is ready
    $(document).ready(function() {
        initMobileMenu();
        initSearchOverlay();
        initStickyHeader();
        initBackToTop();
        initPreloader();
        initScrollAnimations();
        initAjaxAddToCart();
    });
    
    // Initialize when window is loaded
    $(window).on('load', function() {
        $('#worpreesPreloader').addClass('hidden');
    });
    
    // Initialize when window is scrolled
    $(window).on('scroll', function() {
        handleScroll();
    });
    
    /**
     * Mobile Menu
     */
    function initMobileMenu() {
        var $mobileMenu = $('#worpreesMobileMenu');
        var $mobileMenuBtn = $('.mobile-menu-btn');
        var $mobileMenuClose = $('.mobile-menu-close');
        
        if ($mobileMenu.length && $mobileMenuBtn.length) {
            $mobileMenuBtn.on('click', function(e) {
                e.preventDefault();
                $mobileMenu.addClass('active');
                $('body').addClass('mobile-menu-open');
            });
            
            $mobileMenuClose.on('click', function(e) {
                e.preventDefault();
                $mobileMenu.removeClass('active');
                $('body').removeClass('mobile-menu-open');
            });
            
            $mobileMenu.find('a').on('click', function() {
                $mobileMenu.removeClass('active');
                $('body').removeClass('mobile-menu-open');
            });
            
            $(document).on('keydown', function(e) {
                if (e.key === 'Escape' && $mobileMenu.hasClass('active')) {
                    $mobileMenu.removeClass('active');
                    $('body').removeClass('mobile-menu-open');
                }
            });
        }
    }
    
    /**
     * Search Overlay
     */
    function initSearchOverlay() {
        var $searchOverlay = $('#worpreesSearchOverlay');
        var $searchBtn = $('.search-btn');
        var $searchClose = $('.search-overlay-close');
        
        if ($searchOverlay.length && $searchBtn.length) {
            $searchBtn.on('click', function(e) {
                e.preventDefault();
                $searchOverlay.addClass('active');
                $('body').addClass('search-open');
                $searchOverlay.find('.search-field').focus();
            });
            
            $searchClose.on('click', function(e) {
                e.preventDefault();
                $searchOverlay.removeClass('active');
                $('body').removeClass('search-open');
            });
            
            $searchOverlay.on('click', function(e) {
                if (e.target === this) {
                    $searchOverlay.removeClass('active');
                    $('body').removeClass('search-open');
                }
            });
            
            $(document).on('keydown', function(e) {
                if (e.key === 'Escape' && $searchOverlay.hasClass('active')) {
                    $searchOverlay.removeClass('active');
                    $('body').removeClass('search-open');
                }
            });
        }
    }
    
    /**
     * Sticky Header
     */
    function initStickyHeader() {
        var $header = $('.header');
        
        if ($header.length) {
            $(window).on('scroll', function() {
                if ($(this).scrollTop() > 100) {
                    $header.addClass('sticky');
                } else {
                    $header.removeClass('sticky');
                }
            });
        }
    }
    
    /**
     * Back to Top Button
     */
    function initBackToTop() {
        var $backToTop = $('#worpreesBackToTop');
        
        if ($backToTop.length) {
            $(window).on('scroll', function() {
                if ($(this).scrollTop() > 300) {
                    $backToTop.addClass('visible');
                } else {
                    $backToTop.removeClass('visible');
                }
            });
            
            $backToTop.on('click', function(e) {
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: 0
                }, 800, 'easeInOutCubic');
            });
        }
    }
    
    /**
     * Preloader
     */
    function initPreloader() {
        if ($('#worpreesPreloader').length) {
            $('body').addClass('loading');
            
            $('#worpreesPreloader').on('transitionend', function() {
                if ($(this).hasClass('hidden')) {
                    $('body').removeClass('loading');
                }
            });
        }
    }
    
    /**
     * Scroll Animations
     */
    function initScrollAnimations() {
        if (typeof IntersectionObserver !== 'undefined') {
            var observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        $(entry.target).addClass('animated');
                    }
                });
            }, {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            });
            
            $('.animate-on-scroll').each(function() {
                observer.observe(this);
            });
        }
    }
    
    /**
     * AJAX Add to Cart
     */
    function initAjaxAddToCart() {
        var $addToCartBtns = $('.add-to-cart-btn');
        
        if ($addToCartBtns.length) {
            $addToCartBtns.on('click', function(e) {
                e.preventDefault();
                
                var productId = $(this).data('product_id');
                var $btn = $(this);
                
                if (productId) {
                    $.ajax({
                        url: worpreesShopData.ajaxurl,
                        type: 'POST',
                        data: {
                            action: 'worprees_shop_add_to_cart',
                            product_id: productId,
                            quantity: 1,
                            nonce: worpreesShopData.nonce
                        },
                        success: function(response) {
                            if (response.success) {
                                $btn.addClass('added');
                                $btn.html('<i class="fas fa-check"></i> ' + worpreesShopData.added_to_cart_text);
                                
                                // Update cart count
                                $('.cart-count').text(response.data.count).show();
                                
                                // Reset button after delay
                                setTimeout(function() {
                                    $btn.removeClass('added');
                                    $btn.html('<i class="fas fa-shopping-cart"></i> ' + worpreesShopData.add_to_cart_text);
                                }, 2000);
                            } else {
                                alert(response.data);
                            }
                        },
                        error: function() {
                            alert(worpreesShopData.error_message);
                        }
                    });
                }
            });
        }
    }
    
    /**
     * Handle Scroll
     */
    function handleScroll() {
        // Can be extended for more scroll-based features
    }
    
})(jQuery);
