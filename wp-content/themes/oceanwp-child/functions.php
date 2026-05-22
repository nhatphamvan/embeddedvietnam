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
    if ( $post && get_post_meta( $post->ID, '_elementor_edit_mode', true ) === 'builder' ) {
        remove_all_actions( 'ocean_top_bar' );
        remove_all_actions( 'ocean_header' );
    }
} );

// Ẩn OceanWP page title + breadcrumb trên TẤT CẢ Elementor pages
add_filter( 'ocean_display_page_header', function( $display ) {
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

// Load child theme includes
require_once get_stylesheet_directory() . '/inc/customizer.php';
require_once get_stylesheet_directory() . '/inc/seo.php';
require_once get_stylesheet_directory() . '/inc/images.php';
