<?php
/**
 * Homepage template — embedded.io.vn
 * Layout: Hero → Featured Posts → Categories → Latest Posts
 */
get_header(); ?>

<?php do_action( 'ocean_before_content_wrap' ); ?>

<!-- ===== HERO ===== -->
<section class="eio-hero">
	<div class="container">
		<div class="eio-hero__content">
			<span class="eio-hero__badge">🇻🇳 Vietnam Embedded Community</span>
			<h1 class="eio-hero__title">Kiến thức Embedded,<br>từ người làm thật</h1>
			<p class="eio-hero__desc">
				Cộng đồng chia sẻ kiến thức kỹ thuật nhúng, firmware, RTOS, IoT và cơ hội nghề nghiệp tại Việt Nam.
			</p>
			<div class="eio-hero__actions">
				<a href="<?php echo esc_url( home_url( '/blog' ) ); ?>" class="btn btn-primary">Đọc bài viết</a>
				<a href="<?php echo esc_url( home_url( '/tuyen-dung' ) ); ?>" class="btn btn-outline">Xem tuyển dụng</a>
			</div>
		</div>
	</div>
</section>

<!-- ===== FEATURED POSTS ===== -->
<section class="eio-section eio-featured">
	<div class="container">
		<div class="eio-section__header">
			<h2>Bài viết nổi bật</h2>
			<a href="<?php echo esc_url( home_url( '/blog' ) ); ?>" class="see-all">Xem tất cả →</a>
		</div>

		<div class="eio-grid eio-grid--3">
			<?php
			$featured = new WP_Query( array(
				'posts_per_page' => 3,
				'tag'            => 'featured',
				'no_found_rows'  => true,
			) );
			if ( ! $featured->have_posts() ) {
				$featured = new WP_Query( array( 'posts_per_page' => 3, 'no_found_rows' => true ) );
			}
			while ( $featured->have_posts() ) :
				$featured->the_post();
				?>
				<article class="eio-card">
					<?php if ( has_post_thumbnail() ) : ?>
					<a href="<?php the_permalink(); ?>" class="eio-card__thumb">
						<?php the_post_thumbnail( 'medium_large' ); ?>
					</a>
					<?php endif; ?>
					<div class="eio-card__body">
						<div class="eio-card__cats"><?php the_category( ' ' ); ?></div>
						<h3 class="eio-card__title">
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</h3>
						<p class="eio-card__excerpt"><?php echo wp_trim_words( get_the_excerpt(), 18 ); ?></p>
						<div class="eio-card__meta">
							<?php echo get_avatar( get_the_author_meta( 'ID' ), 24 ); ?>
							<span><?php the_author(); ?></span>
							<span class="sep">·</span>
							<time><?php echo get_the_date( 'd/m/Y' ); ?></time>
						</div>
					</div>
				</article>
			<?php endwhile; wp_reset_postdata(); ?>
		</div>

	</div>
</section>

<!-- ===== CATEGORY PILLS ===== -->
<section class="eio-section eio-cats-section">
	<div class="container">
		<h2>Khám phá theo chủ đề</h2>
		<div class="eio-cat-pills">
			<?php
			$cats = get_categories( array( 'number' => 8, 'hide_empty' => true ) );
			foreach ( $cats as $cat ) : ?>
				<a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>" class="eio-cat-pill">
					<?php echo esc_html( $cat->name ); ?>
					<span class="count"><?php echo esc_html( $cat->count ); ?></span>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- ===== LATEST POSTS ===== -->
<section class="eio-section eio-latest">
	<div class="container eio-two-col">

		<div class="eio-main-col">
			<div class="eio-section__header">
				<h2>Mới nhất</h2>
			</div>
			<div class="eio-list">
				<?php
				$latest = new WP_Query( array( 'posts_per_page' => 6, 'offset' => 3, 'no_found_rows' => false ) );
				while ( $latest->have_posts() ) :
					$latest->the_post(); ?>
					<article class="eio-list-item">
						<?php if ( has_post_thumbnail() ) : ?>
						<a href="<?php the_permalink(); ?>" class="eio-list-item__thumb">
							<?php the_post_thumbnail( 'thumbnail' ); ?>
						</a>
						<?php endif; ?>
						<div class="eio-list-item__body">
							<div class="eio-card__cats"><?php the_category( ' ' ); ?></div>
							<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
							<div class="eio-card__meta">
								<time><?php echo get_the_date( 'd/m/Y' ); ?></time>
								<span class="sep">·</span>
								<span><?php the_author(); ?></span>
							</div>
						</div>
					</article>
				<?php endwhile; wp_reset_postdata(); ?>
			</div>

			<?php
			$total_pages = $latest->max_num_pages;
			if ( $total_pages > 1 ) : ?>
			<div class="eio-pagination">
				<?php echo paginate_links( array( 'total' => $total_pages ) ); ?>
			</div>
			<?php endif; ?>
		</div>

		<!-- Sidebar -->
		<aside class="eio-sidebar">
			<?php if ( is_active_sidebar( 'sidebar' ) ) : ?>
				<?php dynamic_sidebar( 'sidebar' ); ?>
			<?php endif; ?>
		</aside>

	</div>
</section>

<?php do_action( 'ocean_after_content_wrap' ); ?>
<?php get_footer(); ?>
