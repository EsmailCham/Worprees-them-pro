<?php
/**
 * The footer for our theme
 * 
 * @package Worprees_Shop
 */

// Close content div from header.php
if (!isset($content_closed)) :
    echo '</div>'; // Close #content
endif;

?>

        <footer id="colophon" class="site-footer footer">
            <!-- Footer Widgets -->
            <div class="footer-widgets py-5">
                <div class="container">
                    <div class="row">
                        <?php
                        // Footer Column 1
                        if (is_active_sidebar('footer-1')) :
                            echo '<div class="col-md-3 col-sm-6">';
                            dynamic_sidebar('footer-1');
                            echo '</div>';
                        endif;
                        
                        // Footer Column 2
                        if (is_active_sidebar('footer-2')) :
                            echo '<div class="col-md-3 col-sm-6">';
                            dynamic_sidebar('footer-2');
                            echo '</div>';
                        endif;
                        
                        // Footer Column 3
                        if (is_active_sidebar('footer-3')) :
                            echo '<div class="col-md-3 col-sm-6">';
                            dynamic_sidebar('footer-3');
                            echo '</div>';
                        endif;
                        
                        // Footer Column 4
                        if (is_active_sidebar('footer-4')) :
                            echo '<div class="col-md-3 col-sm-6">';
                            dynamic_sidebar('footer-4');
                            echo '</div>';
                        endif;
                        ?>
                    </div>
                </div>
            </div>
            
            <!-- Footer Bottom -->
            <div class="footer-bottom py-4">
                <div class="container">
                    <div class="row align-items-center">
                        <!-- Footer Menu -->
                        <?php if (has_nav_menu('footer')) : ?>
                            <div class="col-md-6">
                                <?php
                                wp_nav_menu(array(
                                    'theme_location' => 'footer',
                                    'menu_id' => 'footer-menu',
                                    'menu_class' => 'footer-menu',
                                    'container' => false,
                                    'depth' => 1,
                                ));
                                ?>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Copyright -->
                        <div class="col-md-6 text-center text-md-left">
                            <p class="copyright-text">
                                <?php echo sprintf(esc_html__('کپی رایت %s - تمام حقوق محفوظ است', 'worprees-shop'), get_bloginfo('name')); ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        
        <!-- Back to Top Button -->
        <button class="back-to-top" id="worpreesBackToTop" aria-label="<?php esc_attr_e('بازگشت به بالا', 'worprees-shop'); ?>">
            <i class="fas fa-chevron-up"></i>
        </button>

<?php wp_footer(); ?>

    </body>
</html>
