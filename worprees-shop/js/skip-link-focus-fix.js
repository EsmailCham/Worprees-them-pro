/**
 * Skip Link Focus Fix
 * 
 * @package Worprees_Shop
 */

(function() {
    'use strict';
    
    // This is a namespaced function
    var skipLinkFocus = {
        init: function() {
            // Add an event listener to the window object
            window.addEventListener('hashchange', this.handleHashChange);
            
            // Also run on page load
            this.handleHashChange();
        },
        
        handleHashChange: function() {
            // Get the hash from the URL
            var hash = window.location.hash;
            
            // If there's a hash, find the element and set focus
            if (hash) {
                var element = document.querySelector(hash);
                if (element) {
                    // Set tabindex if it doesn't exist
                    if (!element.hasAttribute('tabindex')) {
                        element.setAttribute('tabindex', '-1');
                    }
                    
                    // Set focus
                    element.focus();
                    
                    // Scroll to element
                    element.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }
        }
    };
    
    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            skipLinkFocus.init();
        });
    } else {
        skipLinkFocus.init();
    }
    
})();
