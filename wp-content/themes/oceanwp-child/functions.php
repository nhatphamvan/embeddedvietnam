<?php
/**
 * OceanWP Child Theme functions
 * Site: embedded.io.vn — Vietnam Embedded Tech Community
 */

// Enqueue parent + child styles and custom scripts
add_action( 'wp_enqueue_scripts', 'oceanwp_child_enqueue_styles' );
function oceanwp_child_enqueue_styles() {
    $version = wp_get_theme()->get( 'Version' );

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
        get_stylesheet_directory_uri() . '/assets/css/custom.css',
        array( 'oceanwp-child-style' ),
        $version
    );
    wp_enqueue_script(
        'oceanwp-child-custom',
        get_stylesheet_directory_uri() . '/assets/js/custom.js',
        array( 'jquery' ),
        $version,
        true
    );
}

// Load child theme includes
require_once get_stylesheet_directory() . '/inc/customizer.php';
