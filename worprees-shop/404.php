<?php
/**
 * Worprees Shop 404 Template
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

	<!-- 404 Section -->
	<section class="error-section">
		<div class="container">
			<div class="error-content">
				<div class="error-code">404</div>
				<h1 class="error-title">صفحه یافت نشد</h1>
				<p class="error-description">متأسفیم، صفحه‌ای که به دنبال آن هستید وجود ندارد یا حذف شده است.</p>
				
				<div class="error-actions">
					<a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary">
						<i class="fas fa-home"></i> بازگشت به صفحه اصلی
					</a>
					<a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="btn btn-outline">
						<i class="fas fa-shopping-bag"></i> مشاهده فروشگاه
					</a>
				</div>
				
				<!-- Search Form -->
				<div class="error-search">
					<form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
						<div class="search-box">
							<input type="search" placeholder="جستجوی محصولات..." value="<?php echo get_search_query(); ?>" name="s" />
							<button type="submit"><i class="fas fa-search"></i></button>
						</div>
					</form>
				</div>
				
				<!-- Suggestions -->
				<div class="error-suggestions">
					<h3>شاید به دنبال این موارد هستید:</h3>
					<ul>
						<li><a href="<?php echo esc_url(home_url('/')); ?>">صفحه اصلی</a></li>
						<li><a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>">فروشگاه</a></li>
						<li><a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>">وبلاگ</a></li>
						<li><a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>">حساب کاربری</a></li>
						<li><a href="<?php echo esc_url(wc_get_page_permalink('cart')); ?>">سبد خرید</a></li>
					</ul>
				</div>
			</div>
		</div>
	</section>

<?php
get_footer();
