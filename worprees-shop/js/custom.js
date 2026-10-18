/**
 * Worprees Shop Custom JS
 * Main custom JavaScript functionality
 */

jQuery(document).ready(function($) {
    'use strict';

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

    // Initialize Slick Carousel for product sliders
    if (typeof $.fn.slick !== 'undefined') {
        $('.products-slider').slick({
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

    // Initialize Magnific Popup for image lightbox
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

    // Initialize CounterUp for counters
    if (typeof $.fn.counterUp !== 'undefined') {
        $('.counter').counterUp({
            delay: 10,
            time: 1000
        });
    }

    // Initialize Isotope for filtering
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

    // Quick view modal
    var quickViewModal = $('#quick-view-modal');
    
    $(document).on('click', '.quick-view-button', function(e) {
        e.preventDefault();
        var productId = $(this).data('product-id');
        
        // AJAX call to get product data
        $.ajax({
            url: worpreesData.ajaxurl,
            type: 'POST',
            data: {
                action: 'worprees_quick_view',
                product_id: productId,
                nonce: worpreesData.nonce
            },
            beforeSend: function() {
                quickViewModal.find('.modal-content').html('<div class="loading"><i class="fas fa-spinner fa-spin"></i></div>');
            },
            success: function(response) {
                if (response.success) {
                    quickViewModal.find('.modal-content').html(response.data.html);
                    quickViewModal.addClass('visible');
                }
            }
        });
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

    // Add to cart AJAX
    $(document).on('click', '.add-to-cart-button', function(e) {
        e.preventDefault();
        var button = $(this);
        var productId = button.data('product-id');
        var quantity = button.data('quantity') || 1;

        $.ajax({
            url: worpreesData.ajaxurl,
            type: 'POST',
            data: {
                action: 'worprees_add_to_cart',
                product_id: productId,
                quantity: quantity,
                nonce: worpreesData.nonce
            },
            beforeSend: function() {
                button.addClass('loading');
                button.html('<i class="fas fa-spinner fa-spin"></i>');
            },
            success: function(response) {
                if (response.success) {
                    // Update cart count
                    $('.cart-count').text(response.data.cart_count);
                    
                    // Show notification
                    showNotification(response.data.message, 'success');
                    
                    // Reset button
                    button.removeClass('loading');
                    button.html('<i class="fas fa-shopping-cart"></i> ' + worpreesData.add_to_cart_text);
                } else {
                    showNotification(response.data.message || worpreesData.error_message, 'error');
                    button.removeClass('loading');
                    button.html('<i class="fas fa-shopping-cart"></i> ' + worpreesData.add_to_cart_text);
                }
            }
        });
    });

    // Add to wishlist AJAX
    $(document).on('click', '.add-to-wishlist', function(e) {
        e.preventDefault();
        var button = $(this);
        var productId = button.data('product-id');

        $.ajax({
            url: worpreesData.ajaxurl,
            type: 'POST',
            data: {
                action: 'worprees_add_to_wishlist',
                product_id: productId,
                nonce: worpreesData.nonce
            },
            beforeSend: function() {
                button.addClass('loading');
            },
            success: function(response) {
                if (response.success) {
                    button.removeClass('loading');
                    button.addClass('added');
                    button.find('i').removeClass('far fa-heart').addClass('fas fa-heart');
                    showNotification(response.data.message, 'success');
                    updateWishlistCount(response.data.count);
                } else {
                    button.removeClass('loading');
                    showNotification(response.data.message || worpreesData.error_message, 'error');
                }
            }
        });
    });

    // Add to compare AJAX
    $(document).on('click', '.add-to-compare', function(e) {
        e.preventDefault();
        var button = $(this);
        var productId = button.data('product-id');

        $.ajax({
            url: worpreesData.ajaxurl,
            type: 'POST',
            data: {
                action: 'worprees_add_to_compare',
                product_id: productId,
                nonce: worpreesData.nonce
            },
            beforeSend: function() {
                button.addClass('loading');
            },
            success: function(response) {
                if (response.success) {
                    button.removeClass('loading');
                    button.addClass('added');
                    showNotification(response.data.message, 'success');
                    updateCompareCount(response.data.count);
                } else {
                    button.removeClass('loading');
                    showNotification(response.data.message || worpreesData.error_message, 'error');
                }
            }
        });
    });

    // Update wishlist count
    function updateWishlistCount(count) {
        $('.wishlist-count').text(count);
    }

    // Update compare count
    function updateCompareCount(count) {
        $('.compare-count').text(count);
    }

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

    // Search form toggle
    $('#search-button').on('click', function(e) {
        e.preventDefault();
        $('.search-form-container').slideToggle(300);
        $(this).toggleClass('active');
    });

    // Close search form on outside click
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.search-button, .search-form-container').length) {
            $('.search-form-container').slideUp(300);
            $('#search-button').removeClass('active');
        }
    });

    // Newsletter form submission
    $('.newsletter-form form').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var email = form.find('input[type="email"]').val();

        if (!email) {
            showNotification('لطفا آدرس ایمیل خود را وارد کنید', 'error');
            return;
        }

        $.ajax({
            url: worpreesData.ajaxurl,
            type: 'POST',
            data: {
                action: 'worprees_newsletter_subscribe',
                email: email,
                nonce: worpreesData.nonce
            },
            beforeSend: function() {
                form.find('button').addClass('loading');
            },
            success: function(response) {
                form.find('button').removeClass('loading');
                if (response.success) {
                    form.find('input[type="email"]').val('');
                    showNotification(response.data.message, 'success');
                } else {
                    showNotification(response.data.message || worpreesData.error_message, 'error');
                }
            }
        });
    });

    // Contact form submission
    $('.contact-form').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var formData = form.serialize();

        $.ajax({
            url: worpreesData.ajaxurl,
            type: 'POST',
            data: formData + '&action=worprees_contact_form&nonce=' + worpreesData.nonce,
            beforeSend: function() {
                form.find('button[type="submit"]').addClass('loading');
            },
            success: function(response) {
                form.find('button[type="submit"]').removeClass('loading');
                if (response.success) {
                    form.trigger('reset');
                    showNotification(response.data.message, 'success');
                } else {
                    showNotification(response.data.message || worpreesData.error_message, 'error');
                }
            }
        });
    });

    // AJAX search
    $('.search-form input[name="s"]').on('input', function() {
        var query = $(this).val();
        
        if (query.length < 3) {
            $('.search-results').hide().empty();
            return;
        }

        $.ajax({
            url: worpreesData.ajaxurl,
            type: 'POST',
            data: {
                action: 'worprees_search',
                query: query,
                nonce: worpreesData.nonce
            },
            beforeSend: function() {
                $('.search-results').html('<div class="loading"><i class="fas fa-spinner fa-spin"></i></div>').show();
            },
            success: function(response) {
                if (response.success) {
                    $('.search-results').html(response.data.html);
                } else {
                    $('.search-results').html('<div class="no-results">موردی یافت نشد</div>');
                }
            }
        });
    });

    // Load more posts/products
    $(document).on('click', '.load-more-button', function(e) {
        e.preventDefault();
        var button = $(this);
        var page = button.data('page');
        var type = button.data('type');

        $.ajax({
            url: worpreesData.ajaxurl,
            type: 'POST',
            data: {
                action: 'worprees_load_more_' + type,
                page: page,
                nonce: worpreesData.nonce
            },
            beforeSend: function() {
                button.addClass('loading');
            },
            success: function(response) {
                button.removeClass('loading');
                if (response.success) {
                    button.parent().before(response.data.html);
                    button.data('page', page + 1);
                    
                    if (!response.data.has_more) {
                        button.hide();
                    }
                }
            }
        });
    });

    // Product quantity increment/decrement
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

    // Product gallery thumbnail click
    $(document).on('click', '.product-thumbnail-item', function() {
        var mainImage = $(this).closest('.product-gallery').find('.product-main-image img');
        var newImageSrc = $(this).find('img').attr('src');
        mainImage.attr('src', newImageSrc);
    });

    // Product variation selection
    $(document).on('change', '.variation-select', function() {
        var form = $(this).closest('form.variations_form');
        var variationId = $(this).val();

        $.ajax({
            url: worpreesData.ajaxurl,
            type: 'POST',
            data: {
                action: 'worprees_get_variation_data',
                variation_id: variationId,
                nonce: worpreesData.nonce
            },
            success: function(response) {
                if (response.success) {
                    form.find('.product-price').html(response.data.price_html);
                    form.find('.product-availability').html(response.data.availability_html);
                }
            }
        });
    });

    // Copy to clipboard
    $(document).on('click', '.copy-link', function(e) {
        e.preventDefault();
        var url = $(this).data('url');
        
        if (navigator.clipboard) {
            navigator.clipboard.writeText(url).then(function() {
                showNotification('لینک در کلیپ‌بورد کپی شد', 'success');
            });
        } else {
            // Fallback for older browsers
            var tempInput = $('<input>');
            $('body').append(tempInput);
            tempInput.val(url).select();
            document.execCommand('copy');
            tempInput.remove();
            showNotification('لینک در کلیپ‌بورد کپی شد', 'success');
        }
    });

    // View mode toggle
    $(document).on('click', '.view-mode-button', function() {
        var viewMode = $(this).data('view');
        
        // Remove active class from all buttons
        $('.view-mode-button').removeClass('active');
        
        // Add active class to clicked button
        $(this).addClass('active');
        
        // Toggle grid/list view
        if (viewMode === 'grid') {
            $('.products-grid').removeClass('list-view').addClass('grid-view');
        } else {
            $('.products-grid').removeClass('grid-view').addClass('list-view');
        }
    });

    // Floating elements animation
    $(window).on('scroll', function() {
        var scrolled = $(this).scrollTop();
        
        $('.floating-element').each(function() {
            var offset = $(this).offset().top;
            var rate = scrolled * -0.5;
            
            $(this).css({
                'transform': 'translateY(' + rate + 'px)'
            });
        });
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
    $('.product-card, .post-card').on('mouseenter', function() {
        $(this).addClass('hovered');
    }).on('mouseleave', function() {
        $(this).removeClass('hovered');
    });

    // Initialize all animations on scroll
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
