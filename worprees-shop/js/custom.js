/**
 * Worprees Shop Custom JS
 * Main custom JavaScript functionality
 */

jQuery(document).ready(function($) {
    'use strict';

    // Check if worpreesData is defined
    if (typeof worpreesData === 'undefined') {
        worpreesData = {
            ajaxurl: typeof ajaxurl !== 'undefined' ? ajaxurl : '',
            themeurl: '',
            nonce: ''
        };
    }

    // Initialize Wow.js for animations
    if (typeof WOW !== 'undefined') {
        new WOW().init();
    }

    // Initialize AOS for scroll animations
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 1000,
            easing: 'ease-out',
            once: true,
            mirror: false
        });
    }

    // Initialize Slick Carousel
    if (typeof $.fn.slick !== 'undefined') {
        $('.products-slider, .testimonials-slider, .brands-slider').slick({
            rtl: true,
            dots: true,
            infinite: true,
            speed: 500,
            slidesToShow: 4,
            slidesToScroll: 1,
            responsive: [
                {
                    breakpoint: 1200,
                    settings: {
                        slidesToShow: 3
                    }
                },
                {
                    breakpoint: 992,
                    settings: {
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 576,
                    settings: {
                        slidesToShow: 1
                    }
                }
            ]
        });
    }

    // Initialize Magnific Popup
    if (typeof $.fn.magnificPopup !== 'undefined') {
        $('[data-magnific]').magnificPopup({
            type: 'image',
            gallery: {
                enabled: true
            },
            removalDelay: 300,
            mainClass: 'mfp-fade'
        });
    }

    // Initialize CounterUp
    if (typeof $.fn.counterUp !== 'undefined') {
        $('.counter').counterUp({
            delay: 10,
            time: 1000
        });
    }

    // Initialize Isotope
    if (typeof $.fn.isotope !== 'undefined') {
        var $grid = $('.products-grid').isotope({
            itemSelector: '.product-card',
            percentPosition: true,
            masonry: {
                columnWidth: '.product-card'
            }
        });

        // Filter items on button click
        $('.filter-button').on('click', function() {
            var filterValue = $(this).attr('data-filter');
            $grid.isotope({ filter: filterValue });
        });

        // Change active class on filter buttons
        $('.filter-button').on('click', function() {
            $('.filter-button').removeClass('active');
            $(this).addClass('active');
        });
    }

    // Mobile menu toggle
    $('#mobile-menu-button').on('click', function(e) {
        e.preventDefault();
        $(this).toggleClass('active');
        $('.nav-menu').slideToggle(300);
    });

    // Close mobile menu on link click
    $('.nav-menu a').on('click', function() {
        if ($(window).width() < 992) {
            $('.nav-menu').slideUp(300);
            $('#mobile-menu-button').removeClass('active');
        }
    });

    // Sticky header on scroll
    $(window).on('scroll', function() {
        if ($(this).scrollTop() > 100) {
            $('.site-header').addClass('sticky');
        } else {
            $('.site-header').removeClass('sticky');
        }
    });

    // Back to top button
    var backToTopButton = $('#back-to-top');
    
    $(window).on('scroll', function() {
        if ($(this).scrollTop() > 300) {
            backToTopButton.addClass('visible');
        } else {
            backToTopButton.removeClass('visible');
        }
    });

    backToTopButton.on('click', function(e) {
        e.preventDefault();
        $('html, body').animate({ scrollTop: 0 }, 800);
    });

    // Search form toggle
    $('#search-button').on('click', function(e) {
        e.preventDefault();
        $(this).toggleClass('active');
        $('.search-form-container').slideToggle(300);
    });

    // Close search form on outside click
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.search-button, .search-form-container').length) {
            $('.search-form-container').slideUp(300);
            $('#search-button').removeClass('active');
        }
    });

    // Quick view modal
    var quickViewModal = $('#quick-view-modal');
    
    $(document).on('click', '.quick-view-button', function(e) {
        e.preventDefault();
        var productId = $(this).data('product-id');
        
        // Simple quick view without AJAX for demo
        quickViewModal.find('.modal-body').html('<div class="loading"><i class="fas fa-spinner fa-spin"></i></div>');
        quickViewModal.addClass('visible');
        
        // For actual implementation, use AJAX:
        // $.ajax({
        //     url: worpreesData.ajaxurl,
        //     type: 'POST',
        //     data: {
        //         action: 'worprees_quick_view',
        //         product_id: productId,
        //         nonce: worpreesData.nonce
        //     },
        //     success: function(response) {
        //         if (response.success) {
        //             quickViewModal.find('.modal-body').html(response.data.html);
        //         }
        //     }
        // });
    });

    // Close modal on close button click
    $(document).on('click', '.modal-close', function() {
        quickViewModal.removeClass('visible');
    });

    // Close modal on outside click
    quickViewModal.on('click', function(e) {
        if (e.target === this) {
            quickViewModal.removeClass('visible');
        }
    });

    // Close modal on escape key
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape') {
            quickViewModal.removeClass('visible');
        }
    });

    // Add to cart button
    $(document).on('click', '.add-to-cart-button', function(e) {
        e.preventDefault();
        var button = $(this);
        var productId = button.data('product-id');
        
        button.addClass('loading');
        button.html('<i class="fas fa-spinner fa-spin"></i>');
        
        setTimeout(function() {
            button.removeClass('loading');
            button.html('<i class="fas fa-shopping-cart"></i> افزودن به سبد');
            showNotification('محصول به سبد خرید اضافه شد', 'success');
        }, 1000);
        
        // For actual implementation, use AJAX:
        // $.ajax({
        //     url: worpreesData.ajaxurl,
        //     type: 'POST',
        //     data: {
        //         action: 'worprees_add_to_cart',
        //         product_id: productId,
        //         quantity: 1,
        //         nonce: worpreesData.nonce
        //     },
        //     success: function(response) {
        //         if (response.success) {
        //             $('.cart-count').text(response.data.cart_count);
        //             showNotification(response.data.message, 'success');
        //         }
        //     }
        // });
    });

    // Add to wishlist
    $(document).on('click', '.add-to-wishlist', function(e) {
        e.preventDefault();
        var button = $(this);
        var productId = button.data('product-id');
        
        button.addClass('loading');
        
        setTimeout(function() {
            button.removeClass('loading');
            button.addClass('added');
            button.find('i').removeClass('far fa-heart').addClass('fas fa-heart');
            showNotification('محصول به علاقه‌مندها اضافه شد', 'success');
        }, 500);
    });

    // Add to compare
    $(document).on('click', '.add-to-compare', function(e) {
        e.preventDefault();
        var button = $(this);
        var productId = button.data('product-id');
        
        button.addClass('loading');
        
        setTimeout(function() {
            button.removeClass('loading');
            button.addClass('added');
            showNotification('محصول به لیست مقایسه اضافه شد', 'success');
        }, 500);
    });

    // Show notification
    function showNotification(message, type) {
        var notification = $('<div class="notification ' + type + '">' + message + '</div>');
        $('body').append(notification);
        
        setTimeout(function() {
            notification.fadeOut(300, function() {
                notification.remove();
            });
        }, 3000);
    }

    // Quantity increment/decrement
    $(document).on('click', '.quantity-minus', function() {
        var input = $(this).siblings('.quantity-input');
        var currentValue = parseInt(input.val());
        var minValue = parseInt(input.attr('min')) || 1;
        
        if (currentValue > minValue) {
            input.val(currentValue - 1);
        }
    });

    $(document).on('click', '.quantity-plus', function() {
        var input = $(this).siblings('.quantity-input');
        var currentValue = parseInt(input.val());
        var maxValue = parseInt(input.attr('max')) || 999;
        
        if (currentValue < maxValue) {
            input.val(currentValue + 1);
        }
    });

    // View mode toggle
    $(document).on('click', '.view-mode-button', function() {
        var viewMode = $(this).data('view');
        
        $('.view-mode-button').removeClass('active');
        $(this).addClass('active');
        
        if (viewMode === 'grid') {
            $('.products-grid').removeClass('list-view').addClass('grid-view');
        } else {
            $('.products-grid').removeClass('grid-view').addClass('list-view');
        }
    });

    // Newsletter form
    $('.newsletter-form form').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var email = form.find('input[type="email"]').val();
        
        if (!email) {
            showNotification('لطفا آدرس ایمیل خود را وارد کنید', 'error');
            return;
        }
        
        form.find('button').addClass('loading');
        
        setTimeout(function() {
            form.find('button').removeClass('loading');
            form.find('input[type="email"]').val('');
            showNotification('با موفقیت ثبت نام شدید', 'success');
        }, 1000);
    });

    // Parallax effect
    if ($('.parallax').length) {
        $(window).on('scroll', function() {
            var scroll = $(window).scrollTop();
            var parallax = $('.parallax');
            var speed = 0.5;
            
            parallax.css({
                'background-position': 'center ' + (-scroll * speed) + 'px'
            });
        });
    }

    // Hover effects
    $('.product-card, .post-card, .category-card').on('mouseenter', function() {
        $(this).addClass('hovered');
    }).on('mouseleave', function() {
        $(this).removeClass('hovered');
    });

    // Initialize animations on scroll
    if (typeof IntersectionObserver !== 'undefined') {
        var observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    $(entry.target).addClass('animate');
                }
            });
        }, {
            threshold: 0.1
        });

        $('.animate-on-scroll').each(function() {
            observer.observe(this);
        });
    }
});

// Window load events
jQuery(window).on('load', function() {
    'use strict';

    // Hide preloader
    $('.site-preloader').fadeOut(500);

    // Initialize animations
    setTimeout(function() {
        $('body').addClass('loaded');
    }, 500);
});
