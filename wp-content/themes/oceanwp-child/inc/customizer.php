<?php
/**
 * Customizer — OceanWP Child (embedded.io.vn)
 */
add_action( 'customize_register', function ( $wp_customize ) {

    // ── Section: Branding ────────────────────────────────
    $wp_customize->add_section( 'embeddedio_branding', [
        'title'    => 'Embedded Vietnam — Branding',
        'priority' => 28,
    ] );

    _embeddedio_text( $wp_customize, 'embeddedio_accent_color', 'embeddedio_branding',
        'Primary Color (hex)', '#1a73e8', 'sanitize_hex_color' );

    // ── Section: Footer ──────────────────────────────────
    $wp_customize->add_section( 'embeddedio_footer', [
        'title'    => 'Embedded Vietnam — Footer',
        'priority' => 29,
    ] );

    _embeddedio_text( $wp_customize, 'embeddedio_scrolling_text', 'embeddedio_footer',
        'Scrolling text (HTML allowed)',
        'RTOS &nbsp; • &nbsp; AI &nbsp; • &nbsp; Machine Learning &nbsp; • &nbsp; Embedded RTOS Vietnam',
        'wp_kses_post' );

    _embeddedio_text( $wp_customize, 'embeddedio_footer_copyright', 'embeddedio_footer',
        'Copyright text (HTML allowed)',
        'Copyright &copy; Embedded RTOS Vietnam.',
        'wp_kses_post' );

    // ── Section: Social Links ────────────────────────────
    $wp_customize->add_section( 'embeddedio_social', [
        'title'    => 'Embedded Vietnam — Social Links',
        'priority' => 30,
    ] );

    $socials = [
        'twitter'  => [ 'Twitter / X',  'https://x.com' ],
        'facebook' => [ 'Facebook',     'https://www.facebook.com/emrtosVN/' ],
        'linkedin' => [ 'LinkedIn',     'https://linkedin.com' ],
        'youtube'  => [ 'YouTube',      'https://youtube.com' ],
        'rss'      => [ 'RSS (để trống = tự động)', '' ],
        'github'   => [ 'GitHub',       'https://github.com/embeddedrtos' ],
    ];

    foreach ( $socials as $id => [ $label, $default ] ) {
        _embeddedio_text( $wp_customize, "embeddedio_social_{$id}", 'embeddedio_social',
            $label, $default, 'esc_url_raw' );
    }
} );

// ── Helper: add text setting + control in one call ───────
function _embeddedio_text( $wpc, $id, $section, $label, $default, $sanitize ): void {
    $wpc->add_setting( $id, [
        'default'           => $default,
        'sanitize_callback' => $sanitize,
        'transport'         => 'refresh',
    ] );
    $wpc->add_control( $id, [
        'label'   => $label,
        'section' => $section,
        'type'    => 'text',
    ] );
}

// ── Output primary color CSS variable ────────────────────
add_action( 'wp_head', function () {
    $color = sanitize_hex_color( get_theme_mod( 'embeddedio_accent_color', '#1a73e8' ) );
    if ( $color ) {
        printf( "<style>:root{--primary:%s;}</style>\n", esc_attr( $color ) );
    }
} );
