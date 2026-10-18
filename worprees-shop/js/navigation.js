/**
 * Navigation JavaScript
 * 
 * @package Worprees_Shop
 */

(function($) {
    'use strict';
    
    $(document).ready(function() {
        // Mobile menu toggle
        $('.menu-toggle').on('click', function(e) {
            e.preventDefault();
            var $menu = $('#primary-menu');
            var $button = $(this);
            var expanded = $button.attr('aria-expanded') === 'true' || false;
            
            $button.attr('aria-expanded', !expanded);
            $menu.toggleClass('active');
        });
        
        // Close mobile menu when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.main-navigation').length && !$(e.target).is('.menu-toggle')) {
                $('#primary-menu').removeClass('active');
                $('.menu-toggle').attr('aria-expanded', 'false');
            }
        });
        
        // Close mobile menu with Escape key
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape') {
                $('#primary-menu').removeClass('active');
                $('.menu-toggle').attr('aria-expanded', 'false');
            }
        });
    });
    
})(jQuery);
