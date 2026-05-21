<?php
if ( ! defined( 'ABSPATH' ) ) exit;
$links = embeddedio_footer_social_links();
$links = array_filter( $links, fn( $l ) => ! empty( $l['url'] ) );
if ( empty( $links ) ) return;
?>

<nav class="custom-footer-socials" aria-label="<?php esc_attr_e( 'Social media links', 'oceanwp-child' ); ?>">
	<?php foreach ( $links as $link ) : ?>
		<a href="<?php echo esc_url( $link['url'] ); ?>"
		   target="_blank"
		   rel="noopener noreferrer"
		   aria-label="<?php echo esc_attr( $link['label'] ); ?>">
			<i class="<?php echo esc_attr( $link['icon'] ); ?>" aria-hidden="true"></i>
		</a>
	<?php endforeach; ?>
</nav>
