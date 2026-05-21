<?php
add_action( 'wp_enqueue_scripts', 'oceanwp_child_enqueue_styles' );
function oceanwp_child_enqueue_styles() {
    wp_enqueue_style(
        'oceanwp-style',
        get_template_directory_uri() . '/style.css'
    );
    wp_enqueue_style(
        'oceanwp-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( 'oceanwp-style' ),
        wp_get_theme()->get( 'Version' )
    );
}
