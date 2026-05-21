<?php
/**
 * Footer configuration — single source of truth
 * Thay đổi link/text ở đây, không cần sửa template
 */

function embeddedio_footer_social_links(): array {
    return [
        [
            'id'    => 'twitter',
            'url'   => get_theme_mod( 'embeddedio_social_twitter', 'https://x.com' ),
            'icon'  => 'fab fa-twitter',
            'label' => 'Twitter / X',
        ],
        [
            'id'    => 'facebook',
            'url'   => get_theme_mod( 'embeddedio_social_facebook', 'https://www.facebook.com/emrtosVN/' ),
            'icon'  => 'fab fa-facebook-f',
            'label' => 'Facebook',
        ],
        [
            'id'    => 'linkedin',
            'url'   => get_theme_mod( 'embeddedio_social_linkedin', 'https://linkedin.com' ),
            'icon'  => 'fab fa-linkedin-in',
            'label' => 'LinkedIn',
        ],
        [
            'id'    => 'youtube',
            'url'   => get_theme_mod( 'embeddedio_social_youtube', 'https://youtube.com' ),
            'icon'  => 'fab fa-youtube',
            'label' => 'YouTube',
        ],
        [
            'id'    => 'rss',
            'url'   => get_theme_mod( 'embeddedio_social_rss', get_feed_link() ),
            'icon'  => 'fas fa-rss',
            'label' => 'RSS Feed',
        ],
        [
            'id'    => 'github',
            'url'   => get_theme_mod( 'embeddedio_social_github', 'https://github.com/embeddedrtos' ),
            'icon'  => 'fab fa-github',
            'label' => 'GitHub',
        ],
    ];
}

function embeddedio_footer_scrolling_text(): string {
    $default = 'RTOS &nbsp; • &nbsp; AI &nbsp; • &nbsp; Machine Learning &nbsp; • &nbsp; Embedded RTOS Vietnam';
    return get_theme_mod( 'embeddedio_scrolling_text', $default );
}

function embeddedio_footer_copyright(): string {
    $default = 'Copyright &copy; Embedded RTOS Vietnam.';
    return get_theme_mod( 'embeddedio_footer_copyright', $default );
}
