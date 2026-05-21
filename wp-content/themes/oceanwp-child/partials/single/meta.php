<?php
/**
 * Single post meta — child theme override
 * Thêm: reading time, view count, category badge
 */
if ( ! defined( 'ABSPATH' ) ) exit;

$sections  = oceanwp_blog_single_meta();
$post_type = get_post_type();
$allowed   = apply_filters( 'oceanwp_single_post_header_allowed_post_types', array( 'post' ) );

if ( empty( $sections ) || ! in_array( $post_type, $allowed, true ) ) return;
if ( 'quote' === get_post_format() ) return;

$ocean_date_onoff  = apply_filters( 'ocean_single_modified_date_state', false );
$display_mod_date  = ( false === $ocean_date_onoff || ( true === $ocean_date_onoff && ( get_the_date() !== get_the_modified_date() ) ) );

do_action( 'ocean_before_single_post_meta' );
?>

<div class="embeddedio-post-meta">

	<?php if ( has_category() ) : ?>
	<div class="post-meta-categories">
		<?php the_category( ' ' ); ?>
	</div>
	<?php endif; ?>

	<ul class="meta clr">

		<?php foreach ( $sections as $section ) : ?>

			<?php if ( 'author' === $section ) : ?>
			<li class="meta-author" <?php oceanwp_schema_markup( 'author_name' ); ?>>
				<?php echo get_avatar( get_the_author_meta( 'ID' ), 24, '', '', array( 'class' => 'meta-avatar' ) ); ?>
				<?php the_author_posts_link(); ?>
			</li>
			<?php endif; ?>

			<?php if ( 'date' === $section ) : ?>
			<li class="meta-date" <?php oceanwp_schema_markup( 'publish_date' ); ?>>
				<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
					<?php echo get_the_date( 'd/m/Y' ); ?>
				</time>
			</li>
			<?php endif; ?>

			<?php if ( 'reading-time' === $section ) : ?>
			<li class="meta-rt">
				⏱ <?php echo esc_html( ocean_reading_time() ); ?>
			</li>
			<?php endif; ?>

			<?php if ( 'comments' === $section && comments_open() && ! post_password_required() ) : ?>
			<li class="meta-comments">
				<?php comments_popup_link( '0 bình luận', '1 bình luận', '% bình luận', 'comments-link' ); ?>
			</li>
			<?php endif; ?>

		<?php endforeach; ?>

	</ul>

</div><!-- .embeddedio-post-meta -->

<?php do_action( 'ocean_after_single_post_meta' ); ?>
