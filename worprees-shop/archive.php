<?php
/**
 * Worprees Shop Archive Template
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

	<!-- Archive Header -->
	<div class="archive-header">
		<div class="container">
			<?php
			// Archive title
			if (is_category()) {
				$title = single_cat_title('', false);
			} elseif (is_tag()) {
				$title = single_tag_title('', false);
			} elseif (is_author()) {
				$title = get_the_author();
			} elseif (is_date()) {
				$title = get_the_date();
			} elseif (is_post_type_archive()) {
				$title = post_type_archive_title('', false);
			} elseif (is_search()) {
				$title = 'نتایج جستجو برای: ' . get_search_query();
			} else {
				$title = 'آرشیو';
			}
			
			echo '<h1 class="archive-title">' . esc_html($title) . '</h1>';
			
			// Breadcrumb
			if (function_exists('worprees_shop_breadcrumbs')) {
				worprees_shop_breadcrumbs();
			}
			
			// Description
			if (is_category() || is_tag() || is_post_type_archive()) {
				$description = get_the_archive_description();
				if ($description) {
					echo '<div class="archive-description">' . wp_kses_post($description) . '</div>';
				}
			}
			?>
		</div>
	</div>

	<!-- Main Content -->
	<div class="archive-content">
		<div class="container">
			<div class="archive-row">
				
				<!-- Main Column -->
				<div class="archive-main">
					
					<?php
					// Check if it's WooCommerce archive
					if (function_exists('is_woocommerce') && (is_woocommerce() || is_shop() || is_product_category() || is_product_tag())) {
						
						// WooCommerce archive
						if (have_posts()) {
							echo '<div class="products-grid">';
							
							while (have_posts()) : the_post();
								global $product;
								
								if (function_exists('wc_get_product')) {
									$product = wc_get_product(get_the_ID());
								}
								
								if ($product && $product->is_visible()) {
									echo '<div class="product-card animate-fadeInUp">';
									
									echo '<div class="product-thumbnail">';
									if (has_post_thumbnail()) {
										the_post_thumbnail('worprees-product');
									}
									
									// Product badges
									if ($product->is_on_sale()) {
										echo '<span class="product-badge product-badge-sale">فروش ویژه</span>';
									}
									if ($product->is_featured()) {
										echo '<span class="product-badge product-badge-featured">ویژه</span>';
									}
									if (!$product->is_in_stock()) {
										echo '<span class="product-badge product-badge-out-of-stock">ناموجود</span>';
									}
									
									// Product actions
									echo '<div class="product-actions">';
									echo '<button class="quick-view-button" data-product-id="' . esc_attr(get_the_ID()) . '"><i class="fas fa-search"></i></button>';
									echo '<button class="add-to-wishlist" data-product-id="' . esc_attr(get_the_ID()) . '"><i class="far fa-heart"></i></button>';
									echo '<button class="add-to-compare" data-product-id="' . esc_attr(get_the_ID()) . '"><i class="fas fa-exchange-alt"></i></button>';
									echo '</div>';
									echo '</div>';
									
									echo '<div class="product-info">';
									echo '<h3 class="product-title"><a href="' . esc_url(get_permalink()) . '">' . esc_html(get_the_title()) . '</a></h3>';
									echo '<div class="product-price">' . $product->get_price_html() . '</div>';
									
									// Rating
									if (function_exists('wc_get_rating_html')) {
										echo '<div class="product-rating">' . wc_get_rating_html($product->get_average_rating()) . '</div>';
									}
									
									// Add to cart button
									if ($product->is_purchasable() && $product->is_in_stock()) {
										echo '<button class="add-to-cart-button btn btn-primary" data-product-id="' . esc_attr(get_the_ID()) . '">';
										echo '<i class="fas fa-shopping-cart"></i> افزودن به سبد';
										echo '</button>';
									} else {
										echo '<button class="add-to-cart-button btn btn-primary disabled">ناموجود</button>';
									}
									echo '</div>';
									echo '</div>';
								}
							endwhile;
							
							echo '</div>';
							
							// Pagination
							hecho '<div class="archive-pagination">';
							numeric_posts_nav();
							// or woocommerce_pagination();
							echo '</div>';
						} else {
							echo '<div class="no-results">';
							echo '<p>هیچ محصولی یافت نشد.</p>';
							echo '</div>';
						}
						
					} else {
						// Regular archive (posts, categories, tags, etc.)
						if (have_posts()) {
							echo '<div class="posts-grid">';
							
							while (have_posts()) : the_post();
								echo '<article class="post-card animate-fadeInUp">';
								
								// Post thumbnail
								if (has_post_thumbnail()) {
									echo '<div class="post-thumbnail">';
									if (is_sticky()) {
										echo '<span class="post-badge featured">ویژه</span>';
									}
									the_post_thumbnail('worprees-medium');
									echo '</div>';
								}
								
								echo '<div class="post-info">';
								the_title('<h3 class="post-title"><a href="' . esc_url(get_permalink()) . '">', '</a></h3>');
								
								// Post meta
								echo '<div class="post-meta">';
								echo '<span class="post-date"><i class="far fa-calendar-alt"></i> ' . get_the_date() . '</span>';
								echo '<span class="post-author"><i class="far fa-user"></i> ' . get_the_author() . '</span>';
								if (has_category()) {
									echo '<span class="post-categories"><i class="fas fa-folder-open"></i> ' . get_the_category_list(', ');
								}
								echo '</div>';
								
								// Excerpt
								echo '<div class="post-excerpt">' . get_the_excerpt() . '</div>';
								
								// Read more
								echo '<a href="' . esc_url(get_permalink()) . '" class="btn btn-outline btn-sm">';
								echo 'ادامه مطلب <i class="fas fa-arrow-left"></i>';
								echo '</a>';
								
								echo '</div>';
								echo '</article>';
							endwhile;
							
							echo '</div>';
							
							// Pagination
							echo '<div class="archive-pagination">';
							numeric_posts_nav();
							echo '</div>';
						} else {
							echo '<div class="no-results">';
							echo '<p>هیچ مطلبی یافت نشد.</p>';
							echo '</div>';
						}
					}
					?>
					
				</div>

				<!-- Sidebar -->
				<?php if (is_active_sidebar('sidebar')) : ?>
				<aside class="archive-sidebar">
					<?php dynamic_sidebar('sidebar'); ?>
				</aside>
				<?php endif; ?>

			</div>
		</div>
	</div>

<?php
get_footer();
