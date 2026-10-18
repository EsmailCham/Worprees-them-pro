<?php
/**
 * Worprees Shop Footer Template
 */

if (!defined('ABSPATH')) {
    exit;
}
?>
		</main><!-- #site-content -->

		<!-- Back to Top Button -->
		<button id="back-to-top" class="back-to-top">
			<i class="fas fa-chevron-up"></i>
			<span class="screen-reader-text">بازگشت به بالا</span>
		</button>

		<!-- Site Footer -->
		<footer class="site-footer">
			<div class="container">
				
				<!-- Footer Widgets -->
				<div class="footer-widgets">
					<?php
					for ($i = 1; $i <= 4; $i++) {
						if (is_active_sidebar('footer-' . $i)) {
							echo '<div class="footer-widget">';
							dynamic_sidebar('footer-' . $i);
							echo '</div>';
						}
					}
					?>
				</div>

				<!-- Footer Bottom -->
				<div class="footer-bottom">
					<div class="footer-row">
						<div class="footer-copyright">
							<p>کپی رایت &copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. تمام حقوق محفوظ است.</p>
						</div>
						
						<div class="footer-social">
							<a href="#" title="تلگرام"><i class="fab fa-telegram-plane"></i></a>
							<a href="#" title="اینستاگرام"><i class="fab fa-instagram"></i></a>
							<a href="#" title="واتساپ"><i class="fab fa-whatsapp"></i></a>
							<a href="#" title="تویتر"><i class="fab fa-twitter"></i></a>
							<a href="#" title="فیسبوک"><i class="fab fa-facebook-f"></i></a>
						</div>
					</div>
				</div>

			</div>
		</footer>

		<?php wp_footer(); ?>
	</body>
</html>
