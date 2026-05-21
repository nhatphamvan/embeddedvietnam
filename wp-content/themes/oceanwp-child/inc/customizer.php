<?php
/**
 * Child theme customizer options
 */
add_action( 'customize_register', function ( $wp_customize ) {

    // Section: embedded.io.vn
    $wp_customize->add_section( 'embeddedio_options', array(
        'title'    => __( 'embedded.io.vn', 'oceanwp-child' ),
        'priority' => 30,
    ) );

    // Setting: accent color
    $wp_customize->add_setting( 'embeddedio_accent_color', array(
        'default'           => '#1a73e8',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'refresh',
    ) );
    $wp_customize->add_control( new WP_Customize_Color_Control(
        $wp_customize, 'embeddedio_accent_color', array(
            'label'   => __( 'Primary Color', 'oceanwp-child' ),
            'section' => 'embeddedio_options',
        )
    ) );

    // Setting: footer copyright text
    $wp_customize->add_setting( 'embeddedio_footer_text', array(
        'default'           => '© ' . date('Y') . ' embedded.io.vn — Vietnam Embedded Tech Community',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'embeddedio_footer_text', array(
        'label'   => __( 'Footer Copyright Text', 'oceanwp-child' ),
        'section' => 'embeddedio_options',
        'type'    => 'text',
    ) );
} );

// Output dynamic CSS from customizer
add_action( 'wp_head', function () {
    $color = get_theme_mod( 'embeddedio_accent_color', '#1a73e8' );
    echo '<style>:root { --primary: ' . sanitize_hex_color( $color ) . '; }</style>';
} );
