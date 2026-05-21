<?php
/**
 * Header — OceanWP Child (embedded.io.vn)
 * Adds: reading progress bar + topbar announcement
 */
?>
<!DOCTYPE html>
<html class="<?php echo esc_attr( oceanwp_html_classes() ); ?>" <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?> <?php oceanwp_schema_markup( 'html' ); ?>>

<?php wp_body_open(); ?>

<?php if ( is_single() ) : ?>
<div id="reading-progress-bar" aria-hidden="true"></div>
<?php endif; ?>

<?php do_action( 'ocean_before_outer_wrap' ); ?>

<div id="outer-wrap" class="site clr">

	<a class="skip-link screen-reader-text" href="#main">
		<?php echo esc_html( oceanwp_theme_strings( 'owp-string-header-skip-link', false ) ); ?>
	</a>

	<?php do_action( 'ocean_before_wrap' ); ?>

	<div id="wrap" class="clr">

		<?php do_action( 'ocean_top_bar' ); ?>
		<?php do_action( 'ocean_header' ); ?>
		<?php do_action( 'ocean_before_main' ); ?>

		<main id="main" class="site-main clr" <?php oceanwp_schema_markup( 'main' ); ?> role="main">

			<?php do_action( 'ocean_page_header' ); ?>
