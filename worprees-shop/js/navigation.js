/**
 * Worprees Shop Navigation JS
 * Handles mobile menu toggle functionality
 */

jQuery(document).ready(function($) {
    'use strict';

    // Mobile menu toggle
    $('#mobile-menu-button').on('click', function(e) {
        e.preventDefault();
        $('.nav-menu').slideToggle(300);
        $(this).toggleClass('active');
    });

    // Mobile menu close on link click
    $('.nav-menu a').on('click', function() {
        if ($(window).width() < 768) {
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
});
