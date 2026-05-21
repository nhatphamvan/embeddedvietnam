<?php
/**
 * Footer — OceanWP Child (embedded.io.vn)
 */
?>

	</main><!-- #main -->

	<?php do_action( 'ocean_after_main' ); ?>

	<footer id="footer" class="site-footer" itemscope="itemscope" itemtype="https://schema.org/WPFooter" role="contentinfo">
		<div id="footer-inner" class="clr">
			<div id="footer-bottom" class="clr no-footer-nav">
				<div id="footer-bottom-inner" class="container clr">
					<div id="copyright" class="clr" role="contentinfo">

						<?php get_template_part( 'partials/footer/scrolling-text' ); ?>
						<?php get_template_part( 'partials/footer/social-links' ); ?>
						<?php get_template_part( 'partials/footer/copyright' ); ?>

					</div><!-- #copyright -->
				</div><!-- #footer-bottom-inner -->
			</div><!-- #footer-bottom -->
		</div><!-- #footer-inner -->
	</footer>

</div><!-- #wrap -->
<?php do_action( 'ocean_after_wrap' ); ?>
</div><!-- #outer-wrap -->
<?php do_action( 'ocean_after_outer_wrap' ); ?>

<?php if ( ! class_exists( 'Ocean_Sticky_Footer' ) ) get_template_part( 'partials/scroll-top' ); ?>
<?php if ( 'overlay' === oceanwp_menu_search_style() ) get_template_part( 'partials/header/search-overlay' ); ?>

<?php wp_footer(); ?>
</body>
</html>
