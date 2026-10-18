<?php
/**
 * Worprees Shop Single Post/Product Template
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();

// Check if it's a WooCommerce product
$is_product = function_exists('is_product') && is_product();

if ($is_product) {
    // WooCommerce product template will be loaded by WooCommerce
    // This is just a fallback
    echo '<div class="container py-5">';
    echo '<div class="alert alert-info">';
    echo 'This is a WooCommerce product. The product template should be loaded by WooCommerce.';
    echo '</div>';
    echo '</div>';
} else {
    // Regular post template
    
    while (have_posts()) : the_post();
        ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class('post-single'); ?>>
            
            <!-- Post Header -->
            <header class="post-header">
                <div class="container">
                    <?php
                    // Breadcrumb
                    if (function_exists('worprees_shop_breadcrumbs')) {
                        worprees_shop_breadcrumbs();
                    }
                    
                    // Post title
                    the_title('<h1 class="post-title">', '</h1>');
                    
                    // Post meta
                    echo '<div class="post-meta">';
                    echo '<span class="post-date"><i class="far fa-calendar-alt"></i> ' . get_the_date() . '</span>';
                    echo '<span class="post-author"><i class="far fa-user"></i> ' . get_the_author() . '</span>';
                    if (has_category()) {
                        echo '<span class="post-categories"><i class="fas fa-folder-open"></i> ' . get_the_category_list(', ');
                    }
                    echo '</div>';
                    
                    // Featured image
                    if (has_post_thumbnail()) {
                        echo '<div class="post-thumbnail">';
                        the_post_thumbnail('worprees-large');
                        echo '</div>';
                    }
                    ?>
                </div>
            </header>
            
            <!-- Post Content -->
            <div class="post-content">
                <div class="container">
                    <div class="post-body">
                        <?php the_content(); ?>
                    </div>
                    
                    <!-- Post Tags -->
                    <?php if (has_tag()) : ?>
                    <div class="post-tags">
                        <i class="fas fa-tags"></i>
                        <?php the_tags('', ', ', ''); ?>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Social Sharing -->
                    <div class="post-sharing">
                        <span>اشتراک‌گذاری:</span>
                        <a href="https://telegram.me/share/url?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" target="_blank">
                            <i class="fab fa-telegram-plane"></i>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" target="_blank">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" target="_blank">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://wa.me/?text=<?php echo urlencode(get_the_title() . ' - ' . get_permalink()); ?>" target="_blank">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    </div>
                    
                    <!-- Author Bio -->
                    <?php if (get_the_author_meta('description')) : ?>
                    <div class="author-bio">
                        <div class="author-avatar">
                            <?php echo get_avatar(get_the_author_meta('ID'), 100); ?>
                        </div>
                        <div class="author-info">
                            <h3 class="author-name"><?php the_author(); ?></h3>
                            <p class="author-description"><?php the_author_meta('description'); ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Related Posts -->
                    <?php
                    $related_posts = array();
                    if (has_category()) {
                        $categories = get_the_category();
                        $category_ids = array();
                        foreach ($categories as $category) {
                            $category_ids[] = $category->term_id;
                        }
                        $related_posts = get_posts(array(
                            'category__in' => $category_ids,
                            'post__not_in' => array(get_the_ID()),
                            'posts_per_page' => 3,
                            'orderby' => 'rand'
                        ));
                    }
                    
                    if (!empty($related_posts)) :
                    ?>
                    <div class="related-posts">
                        <h3>پست‌های مرتبط</h3>
                        <div class="related-posts-grid">
                            <?php foreach ($related_posts as $post) : setup_postdata($post); ?>
                            <article class="related-post">
                                <a href="<?php the_permalink(); ?>">
                                    <?php if (has_post_thumbnail()) : ?>
                                    <div class="related-post-thumbnail">
                                        <?php the_post_thumbnail('worprees-medium'); ?>
                                    </div>
                                    <?php endif; ?>
                                    <h4 class="related-post-title"><?php the_title(); ?></h4>
                                </a>
                            </article>
                            <?php endforeach; wp_reset_postdata(); ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Comments -->
                    <?php
                    if (comments_open() || get_comments_number()) {
                        comments_template();
                    }
                    ?>
                </div>
            </div>
            
        </article>
        <?php
    endwhile;
}

get_footer();
