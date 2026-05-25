<?php
/**
 * Serves rcc-clock-tree.svg only to fetch() calls (not direct browser navigation).
 * Sec-Fetch-Dest: empty  → fetch() / XHR  → allowed
 * Sec-Fetch-Dest: document → direct URL    → 403
 */

$dest = $_SERVER['HTTP_SEC_FETCH_DEST'] ?? '';

// Allow fetch() (empty) or missing header (older browsers / server-side)
if ( $dest === 'document' || $dest === 'iframe' ) {
    http_response_code( 403 );
    header( 'Content-Type: text/plain' );
    exit( 'Forbidden' );
}

$file = __DIR__ . '/rcc-clock-tree.svg';
if ( ! file_exists( $file ) ) {
    http_response_code( 404 );
    exit( 'Not found' );
}

header( 'Content-Type: image/svg+xml; charset=utf-8' );
header( 'Cache-Control: private, max-age=3600' );
header( 'X-Content-Type-Options: nosniff' );
readfile( $file );
