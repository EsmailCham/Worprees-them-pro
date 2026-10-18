<?php
/**
 * Worprees Shop Search Results Template
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

	<!-- Search Header -->
	<div class="search-header">
		<div class="container">
			<h1 class="search-title">
				نتایج جستجو برای: 
				<span class="search-query">"<?php echo esc_html(get_search_query()); ?>"</span>
			</h1>
			
			<?php
			// Breadcrumb
			if (function_exists('worprees_shop_breadcrumbs')) {
				worprees_shop_breadcrumbs();
			}
			
			// Search form
			echo '<div class="search-form-container">';
			echo '<form role="search" method="get" action="' . esc_url(home_url('/')) . '">';
			echo '<input type="search" class="search-field" placeholder="جستجوی مجدد..." value="' . esc_attr(get_search_query()) . '" name="s" />';
			echo '<button type="submit" class="search-submit"><i class="fas fa-search"></i></button>';
			echo '</form>';
			echo '</div>';
			?>
		</div>
	</div>

	<!-- Search Results -->
	<div class="search-results">
		<div class="container">
			<?php
			if (have_posts()) {
				echo '<div class="results-count">';
				printf(esc_html(_n('%d نتیجه یافت شد', '%d نتیجه یافت شد', $wp_query->found_posts, 'worprees')));
				echo '</div>';
				
				echo '<div class="results-grid">';
				
				while (have_posts()) : the_post();
					$post_type = get_post_type();
					$post_type_object = get_post_type_object($post_type);
					
					echo '<article class="result-card animate-fadeInUp">';
					
					// Thumbnail
					if (has_post_thumbnail()) {
						echo '<div class="result-thumbnail">';
						if ($post_type === 'product' && function_exists('wc_get_product')) {
							$product = wc_get_product(get_the_ID());
							if ($product->is_on_sale()) {
								echo '<span class="result-badge sale">فروش ویژه</span>';
							}
						}
						the_post_thumbnail('worprees-medium');
						echo '</div>';
					}
					
					echo '<div class="result-info">';
					
					// Title
					echo '<h3 class="result-title">';
					if ($post_type === 'product') {
						echo '<a href="' . esc_url(get_permalink()) . '">' . esc_html(get_the_title()) . '</a>';
					} else {
						the_title('<a href="' . esc_url(get_permalink()) . '">', '</a>');
					}
					echo '</h3>';
					
					// Post type label
					if ($post_type_object) {
						echo '<span class="result-type">' . esc_html($post_type_object->labels->singular_name) . '</span>';
					}
					
					// Meta
					echo '<div class="result-meta">';
					if ($post_type === 'product' && function_exists('wc_get_product')) {
						$product = wc_get_product(get_the_ID());
						echo '<span class="result-price">' . $product->get_price_html() . '</span>';
					} else {
						echo '<span class="result-date"><i class="far fa-calendar-alt"></i> ' . get_the_date() . '</span>';
					}
					echo '</div>';
					
					// Excerpt
					if (has_excerpt()) {
						echo '<div class="result-excerpt">' . get_the_excerpt() . '</div>';
					}
					
					echo '</div>';
					
					// Actions
					if ($post_type === 'product') {
						echo '<div class="result-actions">';
						echo '<button class="add-to-cart-button btn btn-sm" data-product-id="' . esc_attr(get_the_ID()) . '">';
						echo '<i class="fas fa-shopping-cart"></i> افزودن';
						echo '</button>';
						echo '<button class="quick-view-button btn btn-outline btn-sm" data-product-id="' . esc_attr(get_the_ID()) . '">';
						echo '<i class="fas fa-search"></i>';
						echo '</button>';
						echo '</div>';
					}
					
					echo '</article>';
				endwhile;
				
				echo '</div>';
				
				// Pagination
				echo '<div class="results-pagination">';
				numeric_posts_nav();
				echo '</div>';
				
			} else {
				// No results found
				echo '<div class="no-results">';
				echo '<div class="no-results-icon"><i class="fas fa-search"></i></div>';
				echo '<h3>موردی یافت نشد</h3>';
				echo '<p>متأسفیم، هیچ نتیجه‌ای برای "' . esc_html(get_search_query()) . '" یافت نشد.</p>';
				echo '<div class="no-results-actions">';
				echo '<a href="' . esc_url(home_url('/')) . '" class="btn btn-primary">بازگشت به صفحه اصلی</a>';
				echo '<a href="' . esc_url(wc_get_page_permalink('shop')) . '" class="btn btn-outline">مشاهده فروشگاه</a>';
				echo '</div>';
				echo '</div>';
			}
			?>
		</div>
	</div>

<?php
get_footer();
