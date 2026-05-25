<?php
/**
 * Serves rcc-clock-tree.svg only when requested from the lesson page.
 * Direct URL access → 403.
 */

$allowed_origins = [
    'https://embedded.io.vn',
    'http://embedded.io.vn',
];

$referer = $_SERVER['HTTP_REFERER'] ?? '';
$origin  = $_SERVER['HTTP_ORIGIN'] ?? '';

$ok = false;
foreach ( $allowed_origins as $o ) {
    if ( str_starts_with( $referer, $o ) || str_starts_with( $origin, $o ) ) {
        $ok = true;
        break;
    }
}

if ( ! $ok ) {
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
