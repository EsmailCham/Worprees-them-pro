<?php
/**
 * Worprees Shop Page Template
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();

while (have_posts()) : the_post();
    ?>
    
    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <?php
            // Breadcrumb
            if (function_exists('worprees_shop_breadcrumbs')) {
                worprees_shop_breadcrumbs();
            }
            
            // Page title
            the_title('<h1 class="page-title">', '</h1>');
            
            // Featured image
            if (has_post_thumbnail()) {
                echo '<div class="page-thumbnail">';
                the_post_thumbnail('worprees-large');
                echo '</div>';
            }
            ?>
        </div>
    </div>
    
    <!-- Page Content -->
    <div class="page-content">
        <div class="container">
            <div class="page-row">
                
                <!-- Main Content -->
                <div class="page-main">
                    <div class="page-body">
                        <?php the_content(); ?>
                    </div>
                    
                    <!-- Pagination -->
                    <?php
                    wp_link_pages(array(
                        'before' => '<div class="page-links">',
                        'after' => '</div>',
                        'link_before' => '<span>',
                        'link_after' => '</span>',
                        'next_or_number' => 'number',
                    ));
                    ?>
                    
                    <!-- Comments -->
                    <?php
                    if (comments_open() || get_comments_number()) {
                        comments_template();
                    }
                    ?>
                </div>
                
                <!-- Sidebar -->
                <?php if (is_active_sidebar('sidebar')) : ?>
                <aside class="page-sidebar">
                    <?php dynamic_sidebar('sidebar'); ?>
                </aside>
                <?php endif; ?>
                
            </div>
        </div>
    </div>
    
    <?php
endwhile;

get_footer();
