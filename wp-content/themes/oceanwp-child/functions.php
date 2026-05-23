<?php
/**
 * OceanWP Child Theme functions
 * Site: embedded.io.vn — Vietnam Embedded Tech Community
 */

// Enqueue parent + child styles and custom scripts
add_action( 'wp_enqueue_scripts', 'oceanwp_child_enqueue_styles' );
function oceanwp_child_enqueue_styles() {
    $version = wp_get_theme()->get( 'Version' );

    // Inter font — preconnect + stylesheet (display=swap avoids render-blocking)
    wp_enqueue_style(
        'inter-font',
        'https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap',
        array(),
        null
    );

    wp_enqueue_style(
        'oceanwp-style',
        get_template_directory_uri() . '/style.css'
    );
    wp_enqueue_style(
        'oceanwp-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( 'oceanwp-style' ),
        $version
    );
    wp_enqueue_style(
        'oceanwp-child-custom',
        get_stylesheet_directory_uri() . '/assets/css/custom.min.css',
        array( 'oceanwp-child-style', 'inter-font' ),
        $version
    );

    // Vanilla JS — no jQuery dependency
    wp_enqueue_script(
        'oceanwp-child-custom',
        get_stylesheet_directory_uri() . '/assets/js/custom.min.js',
        array(),
        $version,
        true
    );

    // Unified search overlay
    wp_enqueue_style(
        'oceanwp-child-search',
        get_stylesheet_directory_uri() . '/assets/css/search.css',
        array(),
        $version
    );
    wp_enqueue_script(
        'oceanwp-child-search',
        get_stylesheet_directory_uri() . '/assets/js/search.js',
        array(),
        $version,
        true
    );
}

// Preconnect to Google Fonts (reduces latency ~100-200ms)
add_action( 'wp_head', function() {
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
}, 0 );

// Ẩn OceanWP header trên TẤT CẢ page dùng Elementor (Elementor tự build nav)
add_action( 'wp', function() {
    global $post;
    // Guard: never remove header on search/archive pages — $post may be set
    // to the first result (an Elementor page), which would wrongly strip the header.
    if ( is_search() || is_archive() ) return;
    if ( $post && get_post_meta( $post->ID, '_elementor_edit_mode', true ) === 'builder' ) {
        remove_all_actions( 'ocean_top_bar' );
        remove_all_actions( 'ocean_header' );
    }
} );

// Ẩn OceanWP page title + breadcrumb trên TẤT CẢ Elementor pages
add_filter( 'ocean_display_page_header', function( $display ) {
    if ( is_search() || is_archive() ) return $display;
    global $post;
    if ( $post && get_post_meta( $post->ID, '_elementor_edit_mode', true ) === 'builder' ) {
        return false;
    }
    return is_front_page() ? false : $display;
} );

// Redirect native WP search to homepage so the JS overlay handles it
add_action( 'template_redirect', function() {
    if ( is_search() && ! is_admin() ) {
        $q = get_search_query();
        wp_safe_redirect( home_url( '/?search=' . rawurlencode( $q ) ) );
        exit;
    }
} );

// Inject Courses section above Recruitment on homepage
add_action( 'wp_footer', function () {
    if ( ! is_front_page() ) return;
    ?>
<script>
(function () {
    var h = Array.from(document.querySelectorAll('h2,h3,.elementor-heading-title'))
              .find(function (el) { return el.textContent.trim() === 'Recruitment'; });
    if (!h) return;

    var a = h;
    while (a && a !== document.body) {
        if (a.classList.contains('e-parent')) break;
        a = a.parentElement;
    }
    if (!a || a === document.body) return;

    var s = document.createElement('div');
    s.className = 'eio-courses-section';
    s.innerHTML =
        '<div class="wrap container-aligned">' +
          '<div class="eio-section__header">' +
            '<h2>Khóa học</h2>' +
            '<a class="see-all" href="/mcal/">Xem tất cả →</a>' +
          '</div>' +
          '<div class="eio-courses-grid">' +
            '<a class="eio-course-card" href="/mcal/modules/01-mcu/rcc-stm32f411.html">' +
              '<div class="eio-course-card__header">' +
                '<span class="eio-course-card__icon">⏱️</span>' +
                '<div class="eio-course-card__header-text">' +
                  '<div class="eio-course-card__badge">Module 1 — MCU</div>' +
                  '<div class="eio-course-card__header-title">RCC — STM32F411xC/E</div>' +
                '</div>' +
              '</div>' +
              '<div class="eio-course-card__body">' +
                '<p class="eio-course-card__desc">Reset and Clock Control — PLL, bus prescalers, clock tree cho STM32F411.</p>' +
                '<div class="eio-course-card__tags">' +
                  '<span class="eio-course-tag eio-course-tag--theory">Lý thuyết</span>' +
                  '<span class="eio-course-tag eio-course-tag--code">Code</span>' +
                  '<span class="eio-course-tag eio-course-tag--autosar">AUTOSAR</span>' +
                '</div>' +
              '</div>' +
              '<div class="eio-course-card__footer">' +
                '<span>Bài 1 / Module 1</span>' +
                '<span class="eio-course-card__cta">Học ngay →</span>' +
              '</div>' +
            '</a>' +
            '<a class="eio-course-card" href="/mcal/modules/01-mcu/mcu-driver-mcal.html">' +
              '<div class="eio-course-card__header">' +
                '<span class="eio-course-card__icon">🔧</span>' +
                '<div class="eio-course-card__header-text">' +
                  '<div class="eio-course-card__badge">Module 1 — MCU</div>' +
                  '<div class="eio-course-card__header-title">MCU Driver MCAL</div>' +
                '</div>' +
              '</div>' +
              '<div class="eio-course-card__body">' +
                '<p class="eio-course-card__desc">MCU Driver MCAL theo AUTOSAR CP R25-11 cho STM32F4 &amp; STM32F1.</p>' +
                '<div class="eio-course-card__tags">' +
                  '<span class="eio-course-tag eio-course-tag--theory">Lý thuyết</span>' +
                  '<span class="eio-course-tag eio-course-tag--code">Code</span>' +
                  '<span class="eio-course-tag eio-course-tag--autosar">AUTOSAR</span>' +
                '</div>' +
              '</div>' +
              '<div class="eio-course-card__footer">' +
                '<span>Bài 2 / Module 1</span>' +
                '<span class="eio-course-card__cta">Học ngay →</span>' +
              '</div>' +
            '</a>' +
            '<a class="eio-course-card eio-course-card--all" href="/mcal/">' +
              '<span class="all-icon">📚</span>' +
              '<span class="all-label">Xem tất cả khóa họ c</span>' +
              '<span class="all-sub">8 bài · 3 module · Miễn phí</span>' +
            '</a>' +
          '</div>' +
        '</div>';

    a.parentElement.insertBefore(s, a);
})();
</script>
    <?php
}, 20 );

// Load child theme includes
require_once get_stylesheet_directory() . '/inc/customizer.php';
require_once get_stylesheet_directory() . '/inc/seo.php';
require_once get_stylesheet_directory() . '/inc/images.php';
