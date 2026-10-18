/**
 * Worprees Shop Skip Link Focus Fix
 * Handles accessibility skip link focus management
 */

jQuery(document).ready(function($) {
    'use strict';

    // Skip link focus fix for accessibility
    var is_webkit = navigator.userAgent.toLowerCase().indexOf('webkit') > -1;
    var is_opera = navigator.userAgent.toLowerCase().indexOf('opera') > -1;
    var is_ie = navigator.userAgent.toLowerCase().indexOf('msie') > -1;

    if ((is_webkit || is_opera || is_ie) && document.getElementById && window.addEventListener) {
        window.addEventListener('hashchange', function() {
            var id = location.hash.substring(1);
            if (!/^[A-z0-9_-]+$/.test(id)) {
                return;
            }
            var element = document.getElementById(id);
            if (element) {
                if (!/^(?:a|select|input|button|textarea)$/i.test(element.tagName)) {
                    element.tabIndex = -1;
                }
                element.focus();
            }
        }, false);
    }
});
