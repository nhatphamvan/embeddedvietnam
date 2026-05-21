<?php
/**
 * Image Optimization — lazy loading, fetchpriority, decoding=async
 * embedded.io.vn
 */
if ( ! defined( 'ABSPATH' ) ) exit;

// ── All attachment images: lazy load + async decode ───────────────────────
add_filter( 'wp_get_attachment_image_attributes', function ( array $attr ): array {
    $attr['loading']  = $attr['loading']  ?? 'lazy';
    $attr['decoding'] = $attr['decoding'] ?? 'async';
    return $attr;
} );

// ── Featured image on singular pages: fetchpriority=high (LCP fix) ───────
// Only the first post thumbnail on a singular page gets this treatment.
add_filter( 'post_thumbnail_html', function ( string $html ): string {
    static $done = false;
    if ( $done || ! is_singular() || ! in_the_loop() ) return $html;
    $done = true;

    // Switch lazy → eager for the above-fold hero image
    $html = preg_replace( '/\sloading=["\']lazy["\']/i', ' loading="eager"', $html );
    $html = preg_replace( '/\sdecoding=["\']async["\']/i', ' decoding="sync"', $html );

    // Inject fetchpriority only if not already present
    if ( ! str_contains( $html, 'fetchpriority' ) ) {
        $html = str_replace( '<img ', '<img fetchpriority="high" ', $html );
    }

    return $html;
} );

// ── Content images: lazy + async ─────────────────────────────────────────
add_filter( 'the_content', function ( string $content ): string {
    if ( ! str_contains( $content, '<img' ) ) return $content;

    return preg_replace_callback(
        '/<img\b([^>]*?)(\s*\/?>)/i',
        function ( array $m ): string {
            $attrs = $m[1];
            if ( ! preg_match( '/\bloading=/i', $attrs ) ) {
                $attrs .= ' loading="lazy"';
            }
            if ( ! preg_match( '/\bdecoding=/i', $attrs ) ) {
                $attrs .= ' decoding="async"';
            }
            return '<img' . $attrs . $m[2];
        },
        $content
    );
} );

// ── WebP: add image/webp to accepted MIME types for uploads ──────────────
add_filter( 'upload_mimes', function ( array $mimes ): array {
    $mimes['webp'] = 'image/webp';
    return $mimes;
} );

add_filter( 'file_is_displayable_image', function ( bool $result, string $path ): bool {
    if ( ! $result ) {
        $info = @getimagesize( $path );
        if ( isset( $info['mime'] ) && $info['mime'] === 'image/webp' ) {
            return true;
        }
    }
    return $result;
}, 10, 2 );
