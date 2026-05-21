<?php
/**
 * SEO — Canonical, Open Graph, Twitter Card, Schema JSON-LD
 * embedded.io.vn — Vietnam Embedded Tech Community
 */
if ( ! defined( 'ABSPATH' ) ) exit;

// WordPress built-in rel_canonical is minimal; replace with ours
remove_action( 'wp_head', 'rel_canonical' );

// ── 1. Canonical URL ──────────────────────────────────────────────────────
add_action( 'wp_head', 'embeddedio_canonical', 1 );
function embeddedio_canonical(): void {
    if ( is_singular() ) {
        $url = get_permalink();
    } elseif ( is_home() || is_front_page() ) {
        $url = home_url( '/' );
    } elseif ( is_archive() || is_search() ) {
        $url = get_pagenum_link();
    } else {
        return;
    }
    printf( '<link rel="canonical" href="%s" />' . "\n", esc_url( $url ) );
}

// ── 2. Open Graph + Twitter Card ──────────────────────────────────────────
add_action( 'wp_head', 'embeddedio_og_tags', 2 );
function embeddedio_og_tags(): void {
    global $post;

    $site_name = get_bloginfo( 'name' );
    $site_desc = get_bloginfo( 'description' );

    // Defaults (homepage / fallback)
    $og_type  = 'website';
    $og_title = $site_name;
    $og_desc  = $site_desc;
    $og_url   = home_url( '/' );
    $og_image = '';

    if ( is_singular() && $post ) {
        $og_type  = is_single() ? 'article' : 'website';
        $og_title = get_the_title( $post->ID );
        $og_desc  = has_excerpt( $post->ID )
            ? get_the_excerpt()
            : wp_trim_words( wp_strip_all_tags( get_the_content() ), 30, '…' );
        $og_url   = get_permalink( $post->ID );
        $og_image = get_the_post_thumbnail_url( $post->ID, 'large' ) ?: '';
    } elseif ( is_category() || is_tag() || is_tax() ) {
        $term     = get_queried_object();
        $og_title = $term->name . ' — ' . $site_name;
        $og_desc  = $term->description ?: $site_desc;
        $og_url   = get_term_link( $term );
    }

    // Fallback image: site logo
    if ( ! $og_image ) {
        $logo_id  = get_theme_mod( 'custom_logo' );
        $og_image = $logo_id ? wp_get_attachment_image_url( $logo_id, 'large' ) : '';
    }

    // Clamp description to 160 chars
    $og_desc = mb_strimwidth( wp_strip_all_tags( $og_desc ), 0, 160, '…' );

    $tags = [
        // Open Graph
        [ 'property', 'og:type',        $og_type ],
        [ 'property', 'og:locale',      'vi_VN' ],
        [ 'property', 'og:site_name',   $site_name ],
        [ 'property', 'og:title',       $og_title ],
        [ 'property', 'og:description', $og_desc ],
        [ 'property', 'og:url',         $og_url ],
        // Twitter Card
        [ 'name', 'twitter:card',        'summary_large_image' ],
        [ 'name', 'twitter:title',       $og_title ],
        [ 'name', 'twitter:description', $og_desc ],
    ];

    if ( $og_image ) {
        $tags[] = [ 'property', 'og:image',        $og_image ];
        $tags[] = [ 'property', 'og:image:width',  '1200' ];
        $tags[] = [ 'property', 'og:image:height', '630' ];
        $tags[] = [ 'name',     'twitter:image',   $og_image ];
    }

    // Article-specific tags
    if ( is_single() && $post ) {
        $tags[] = [ 'property', 'article:published_time', get_the_date( 'c', $post->ID ) ];
        $tags[] = [ 'property', 'article:modified_time',  get_the_modified_date( 'c', $post->ID ) ];
        $cats   = get_the_category( $post->ID );
        if ( $cats ) {
            $tags[] = [ 'property', 'article:section', $cats[0]->name ];
        }
    }

    echo "\n<!-- Open Graph / Twitter Card -->\n";
    foreach ( $tags as [ $attr, $name, $content ] ) {
        printf(
            '<meta %s="%s" content="%s" />' . "\n",
            esc_attr( $attr ),
            esc_attr( $name ),
            esc_attr( $content )
        );
    }
    echo "\n";
}

// ── 3. Schema.org JSON-LD ─────────────────────────────────────────────────
add_action( 'wp_head', 'embeddedio_schema_jsonld', 3 );
function embeddedio_schema_jsonld(): void {
    global $post;

    $site_name = get_bloginfo( 'name' );
    $site_url  = home_url( '/' );

    if ( is_singular( 'post' ) && $post ) {
        $image_url = get_the_post_thumbnail_url( $post->ID, 'large' ) ?: '';
        $excerpt   = mb_strimwidth(
            wp_strip_all_tags( get_the_excerpt() ?: get_the_content() ),
            0, 160, '…'
        );

        $schema = [
            '@context'         => 'https://schema.org',
            '@type'            => 'BlogPosting',
            'headline'         => get_the_title( $post->ID ),
            'description'      => $excerpt,
            'url'              => get_permalink( $post->ID ),
            'datePublished'    => get_the_date( 'c', $post->ID ),
            'dateModified'     => get_the_modified_date( 'c', $post->ID ),
            'inLanguage'       => 'vi',
            'author'           => [
                '@type' => 'Person',
                'name'  => get_the_author_meta( 'display_name', $post->post_author ),
                'url'   => get_author_posts_url( $post->post_author ),
            ],
            'publisher'        => [
                '@type' => 'Organization',
                'name'  => $site_name,
                'url'   => $site_url,
            ],
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id'   => get_permalink( $post->ID ),
            ],
        ];

        if ( $image_url ) {
            $schema['image'] = [ '@type' => 'ImageObject', 'url' => $image_url ];
        }

        $cats = get_the_category( $post->ID );
        if ( $cats ) {
            $schema['articleSection'] = $cats[0]->name;
            $schema['keywords']       = implode( ', ', array_map( fn( $c ) => $c->name, $cats ) );
        }

    } elseif ( is_home() || is_front_page() ) {
        $schema = [
            '@context'        => 'https://schema.org',
            '@type'           => 'WebSite',
            'name'            => $site_name,
            'url'             => $site_url,
            'inLanguage'      => 'vi',
            'potentialAction' => [
                '@type'       => 'SearchAction',
                'target'      => [
                    '@type'       => 'EntryPoint',
                    'urlTemplate' => $site_url . '?s={search_term_string}',
                ],
                'query-input' => 'required name=search_term_string',
            ],
        ];

    } elseif ( is_category() || is_tag() || is_tax() ) {
        $term   = get_queried_object();
        $schema = [
            '@context'    => 'https://schema.org',
            '@type'       => 'CollectionPage',
            'name'        => $term->name,
            'description' => $term->description ?: '',
            'url'         => get_term_link( $term ),
            'inLanguage'  => 'vi',
        ];

    } else {
        return;
    }

    printf(
        "\n<!-- Schema.org JSON-LD -->\n<script type=\"application/ld+json\">%s</script>\n",
        wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT )
    );
}
