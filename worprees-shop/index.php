<?php
/**
 * Worprees Shop Main Index Template
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

	<!-- Hero Section -->
	<section class="hero-section">
		<div class="hero-bg-pattern"></div>
		<div class="container">
			<div class="hero-content">
				<h1 class="hero-title animate-fadeInUp">به فروشگاه ما خوش آمدید</h1>
				<p class="hero-subtitle animate-fadeInUp delay-200">کامل‌ترین مجموعه محصولات با کیفیت بالا</p>
				<div class="hero-buttons animate-fadeInUp delay-400">
					<a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="btn btn-primary">
						<i class="fas fa-shopping-bag"></i> مشاهده محصولات
					</a>
					<a href="#featured" class="btn btn-outline">
						<i class="fas fa-star"></i> محصولات ویژه
					</a>
				</div>
			</div>
		</div>
	</section>

	<!-- Featured Products Section -->
	<section id="featured" class="products-section">
		<div class="container">
			<h2 class="section-title">محصولات ویژه</h2>
			<div class="products-grid">
				<?php
				$featured_products = array(
					array(
						'title' => 'لپ‌تاپ ایسر مدل Acer Nitro 5',
						'price' => '۴۵,۰۰۰,۰۰۰ تومان',
						'image' => 'https://via.placeholder.com/400x400/6c5ce7/ffffff?text=Laptop',
						'badge' => 'ویژه',
						'rating' => 5
					),
					array(
						'title' => 'موبایل سامسونگ گلکسی S23',
						'price' => '۳۲,۰۰۰,۰۰۰ تومان',
						'image' => 'https://via.placeholder.com/400x400/00cec9/ffffff?text=Phone',
						'badge' => 'فروش ویژه',
						'rating' => 4
					),
					array(
						'title' => 'هدفون بی‌سیم سونی WH-1000XM4',
						'price' => '۸,۵۰۰,۰۰۰ تومان',
						'image' => 'https://via.placeholder.com/400x400/fd79a8/ffffff?text=Headphone',
						'badge' => 'ویژه',
						'rating' => 5
					),
					array(
						'title' => 'ساعت هوشمند اپل واچ Series 8',
						'price' => '۱۲,۰۰۰,۰۰۰ تومان',
						'image' => 'https://via.placeholder.com/400x400/2d3436/ffffff?text=Watch',
						'badge' => 'جدید',
						'rating' => 4
					)
				);

				foreach ($featured_products as $product) :
				?>
				<div class="product-card animate-fadeInUp">
					<div class="product-thumbnail">
						<img src="<?php echo esc_url($product['image']); ?>" alt="<?php echo esc_attr($product['title']); ?>">
						<?php if (!empty($product['badge'])) : ?>
						<span class="product-badge product-badge-<?php echo esc_attr(strtolower($product['badge'])); ?>">
							<?php echo esc_html($product['badge']); ?>
						</span>
						<?php endif; ?>
						<div class="product-actions">
							<button class="quick-view-button" data-product-id="1">
								<i class="fas fa-search"></i>
							</button>
							<button class="add-to-wishlist" data-product-id="1">
								<i class="far fa-heart"></i>
							</button>
							<button class="add-to-compare" data-product-id="1">
								<i class="fas fa-exchange-alt"></i>
							</button>
						</div>
					</div>
					<div class="product-info">
						<h3 class="product-title">
							<a href="#"><?php echo esc_html($product['title']); ?></a>
						</h3>
						<div class="product-price"><?php echo esc_html($product['price']); ?></div>
						<div class="product-rating">
							<?php for ($i = 1; $i <= 5; $i++) : ?>
								<i class="fas fa-star<?php echo $i <= $product['rating'] ? '' : '-half-alt'; ?>"></i>
							<?php endfor; ?>
						</div>
						<button class="add-to-cart-button btn btn-primary" data-product-id="1">
							<i class="fas fa-shopping-cart"></i> افزودن به سبد
						</button>
					</div>
				</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<!-- Latest Products Section -->
	<section class="products-section" style="background: #f8f9fa;">
		<div class="container">
			<h2 class="section-title">جدیدترین محصولات</h2>
			<div class="products-grid">
				<?php for ($i = 1; $i <= 4; $i++) : ?>
				<div class="product-card animate-fadeInUp delay-<?php echo esc_attr($i * 100); ?>">
					<div class="product-thumbnail">
						<img src="https://via.placeholder.com/400x400/6c5ce7/ffffff?text=Product+<?php echo $i; ?>" alt="محصول <?php echo $i; ?>">
						<div class="product-actions">
							<button class="quick-view-button" data-product-id="<?php echo $i; ?>">
								<i class="fas fa-search"></i>
							</button>
							<button class="add-to-wishlist" data-product-id="<?php echo $i; ?>">
								<i class="far fa-heart"></i>
							</button>
							<button class="add-to-compare" data-product-id="<?php echo $i; ?>">
								<i class="fas fa-exchange-alt"></i>
							</button>
						</div>
					</div>
					<div class="product-info">
						<h3 class="product-title">
							<a href="#">محصول نمونه <?php echo $i; ?></a>
						</h3>
						<div class="product-price">۱۵,۰۰۰,۰۰۰ تومان</div>
						<div class="product-rating">
							<i class="fas fa-star"></i>
							<i class="fas fa-star"></i>
							<i class="fas fa-star"></i>
							<i class="fas fa-star"></i>
							<i class="fas fa-star-half-alt"></i>
						</div>
						<button class="add-to-cart-button btn btn-primary" data-product-id="<?php echo $i; ?>">
							<i class="fas fa-shopping-cart"></i> افزودن به سبد
						</button>
					</div>
				</div>
				<?php endfor; ?>
			</div>
			<div class="text-center mt-5">
				<button class="btn btn-outline load-more-button" data-page="2" data-type="products">
					<i class="fas fa-plus"></i> مشاهده بیشتر
				</button>
			</div>
		</div>
	</section>

	<!-- Categories Section -->
	<section class="categories-section">
		<div class="container">
			<h2 class="section-title">دسته‌بندی محصولات</h2>
			<div class="categories-grid">
				<?php
				$categories = array(
					array('name' => 'الکترونیک', 'icon' => 'fas fa-laptop', 'count' => 45),
					array('name' => 'پوشاک', 'icon' => 'fas fa-tshirt', 'count' => 32),
					array('name' => 'لوازم خانگی', 'icon' => 'fas fa-blender', 'count' => 28),
					array('name' => 'ورزش و سرگرمی', 'icon' => 'fas fa-dumbbell', 'count' => 18),
					array('name' => 'کتاب و فرهنگ', 'icon' => 'fas fa-book', 'count' => 22),
					array('name' => 'سلامت و زیبایی', 'icon' => 'fas fa-heart', 'count' => 15)
				);
				
				foreach ($categories as $category) :
				?>
				<div class="category-card animate-fadeInUp">
					<div class="category-icon">
						<i class="<?php echo esc_attr($category['icon']); ?>"></i>
					</div>
					<h3 class="category-name"><?php echo esc_html($category['name']); ?></h3>
					<span class="category-count">(<?php echo esc_html($category['count']); ?> محصول)</span>
					<a href="#" class="category-link">
						<i class="fas fa-arrow-left"></i>
					</a>
				</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<!-- Testimonials Section -->
	<section class="testimonials-section" style="background: #f8f9fa;">
		<div class="container">
			<h2 class="section-title">نظرات مشتریان</h2>
			<div class="testimonials-slider products-slider">
				<?php
				$testimonials = array(
					array(
						'name' => 'محمد رضا',
						'role' => 'مشتری',
						'text' => 'از کیفیت محصولات و خدمات این فروشگاه بسیار راضی هستم. تحویل سریع و بسته‌بندی عالی.',
						'rating' => 5,
						'image' => 'https://via.placeholder.com/100x100/6c5ce7/ffffff?text=MR'
					),
					array(
						'name' => 'fatemeh ا',
						'role' => 'مشتری',
						'text' => 'قیمت‌ها بسیار منصفانه است و تنوع محصولات عالی است. حتماً دوباره خرید خواهم کرد.',
						'rating' => 5,
						'image' => 'https://via.placeholder.com/100x100/00cec9/ffffff?text=F'
					),
					array(
						'name' => 'علی',
						'role' => 'مشتری',
						'text' => 'پشتیبانی مشتریان بسیار خوب و حرفه‌ای است. به همه توصیه می‌کنم.',
						'rating' => 4,
						'image' => 'https://via.placeholder.com/100x100/fd79a8/ffffff?text=A'
					)
				);

				foreach ($testimonials as $testimonial) :
				?>
				<div class="testimonial-card">
					<div class="testimonial-content">
						<div class="testimonial-text">"<?php echo esc_html($testimonial['text']); ?>"</div>
						<div class="testimonial-rating">
							<?php for ($i = 1; $i <= 5; $i++) : ?>
								<i class="fas fa-star<?php echo $i <= $testimonial['rating'] ? '' : '-half-alt'; ?>"></i>
							<?php endfor; ?>
						</div>
					</div>
					<div class="testimonial-author">
						<img src="<?php echo esc_url($testimonial['image']); ?>" alt="<?php echo esc_attr($testimonial['name']); ?>" class="testimonial-avatar">
						<div class="testimonial-author-info">
							<h4 class="testimonial-name"><?php echo esc_html($testimonial['name']); ?></h4>
							<span class="testimonial-role"><?php echo esc_html($testimonial['role']); ?></span>
						</div>
					</div>
				</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<!-- Newsletter Section -->
	<section class="newsletter-section">
		<div class="container">
			<div class="newsletter-content">
				<h2 class="section-title">خبرنامه</h2>
				<p class="newsletter-description">برای دریافت آخرین خبرها و تخفیف‌ها، آدرس ایمیل خود را وارد کنید</p>
				<div class="newsletter-form">
					<form>
						<input type="email" placeholder="آدرس ایمیل خود را وارد کنید" required>
						<button type="submit"><i class="fas fa-paper-plane"></i> ثبت</button>
					</form>
				</div>
			</div>
		</div>
	</section>

	<!-- Brands Section -->
	<section class="brands-section" style="background: #f8f9fa;">
		<div class="container">
			<h2 class="section-title">برندهای شریک</h2>
			<div class="brands-slider products-slider">
				<?php
				$brands = array('Samsung', 'Apple', 'Sony', 'LG', 'Xiaomi', 'Huawei', 'Lenovo', 'Asus');
				foreach ($brands as $brand) :
				?>
				<div class="brand-card">
					<img src="https://via.placeholder.com/150x80/6c5ce7/ffffff?text=<?php echo urlencode($brand); ?>" alt="<?php echo esc_attr($brand); ?>">
				</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

<?php
get_footer();
