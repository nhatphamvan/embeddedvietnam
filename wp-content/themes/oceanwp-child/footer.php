<?php
/**
 * Footer — OceanWP Child (embedded.io.vn)
 */
?>

	</main><!-- #main -->

	<?php do_action( 'ocean_after_main' ); ?>
	<?php do_action( 'ocean_before_footer' ); ?>

	<?php if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'footer' ) ) : ?>

		<footer id="footer" class="site-footer" role="contentinfo">
			<div class="footer-inner container clr">

				<div class="footer-grid">

					<div class="footer-col footer-about">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="footer-logo">
							<?php bloginfo( 'name' ); ?>
						</a>
						<p>Cộng đồng kỹ thuật nhúng Việt Nam — chia sẻ kiến thức, cơ hội nghề nghiệp và xu hướng công nghệ.</p>
						<div class="footer-social">
							<a href="https://facebook.com/embeddedvietnam" target="_blank" rel="noopener" aria-label="Facebook">
								<svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg>
							</a>
							<a href="https://github.com/nhatphamvan/embeddedvietnam" target="_blank" rel="noopener" aria-label="GitHub">
								<svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 00-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0020 4.77 5.07 5.07 0 0019.91 1S18.73.65 16 2.48a13.38 13.38 0 00-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 005 4.77a5.44 5.44 0 00-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 009 18.13V22"/></svg>
							</a>
						</div>
					</div>

					<div class="footer-col">
						<h4 class="footer-heading">Chủ đề</h4>
						<ul>
							<li><a href="<?php echo esc_url( home_url( '/category/embedded' ) ); ?>">Embedded Systems</a></li>
							<li><a href="<?php echo esc_url( home_url( '/category/rtos' ) ); ?>">RTOS</a></li>
							<li><a href="<?php echo esc_url( home_url( '/category/iot' ) ); ?>">IoT</a></li>
							<li><a href="<?php echo esc_url( home_url( '/category/firmware' ) ); ?>">Firmware</a></li>
						</ul>
					</div>

					<div class="footer-col">
						<h4 class="footer-heading">Cộng đồng</h4>
						<ul>
							<li><a href="<?php echo esc_url( home_url( '/tuyen-dung' ) ); ?>">Tuyển dụng</a></li>
							<li><a href="<?php echo esc_url( home_url( '/su-kien' ) ); ?>">Sự kiện</a></li>
							<li><a href="<?php echo esc_url( home_url( '/tac-gia' ) ); ?>">Tác giả</a></li>
							<li><a href="<?php echo esc_url( home_url( '/lien-he' ) ); ?>">Liên hệ</a></li>
						</ul>
					</div>

				</div><!-- .footer-grid -->

				<div class="footer-bottom">
					<p><?php echo esc_html( get_theme_mod( 'embeddedio_footer_text', '© ' . date('Y') . ' embedded.io.vn — Vietnam Embedded Tech Community' ) ); ?></p>
				</div>

			</div><!-- .footer-inner -->
		</footer>

	<?php endif; ?>

	<?php do_action( 'ocean_after_footer' ); ?>

</div><!-- #wrap -->

<?php do_action( 'ocean_after_wrap' ); ?>

</div><!-- #outer-wrap -->

<?php do_action( 'ocean_after_outer_wrap' ); ?>

<?php
if ( ! class_exists( 'Ocean_Sticky_Footer' ) ) {
	get_template_part( 'partials/scroll-top' );
}
if ( 'overlay' === oceanwp_menu_search_style() ) {
	get_template_part( 'partials/header/search-overlay' );
}
if ( 'sidebar' === oceanwp_mobile_menu_style() ) {
	if ( get_theme_mod( 'ocean_mobile_menu_close_btn', true ) ) {
		get_template_part( 'partials/mobile/mobile-sidr-close' );
	}
	get_template_part( 'partials/mobile/mobile-nav' );
	if ( get_theme_mod( 'ocean_mobile_menu_search', true ) ) {
		ob_start();
		get_template_part( 'partials/mobile/mobile-search' );
		echo ob_get_clean();
	}
}
if ( 'fullscreen' === oceanwp_mobile_menu_style() ) {
	get_template_part( 'partials/mobile/mobile-fullscreen' );
}
?>

<?php wp_footer(); ?>
</body>
</html>
