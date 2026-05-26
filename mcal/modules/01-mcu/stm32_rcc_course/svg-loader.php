<?php
$file = __DIR__ . '/rcc-clock-tree.svg';
if ( ! file_exists( $file ) ) {
    http_response_code( 404 );
    exit( 'Not found' );
}
header( 'Content-Type: image/svg+xml; charset=utf-8' );
header( 'Cache-Control: private, max-age=3600' );
readfile( $file );
