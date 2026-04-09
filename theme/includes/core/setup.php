<?php

add_action( 'init', function () {
	load_theme_textdomain( 'matize', get_template_directory() . '/languages' );
} );

// ── Remove unnecessary WordPress head output ──────────────────────────────────
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 );
remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
remove_action( 'template_redirect', 'rest_output_link_header', 11 );
remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );

// ── Remove Gutenberg global styles (unused — no block editor) ─────────────────
remove_action( 'wp_enqueue_scripts', 'wp_enqueue_global_styles' );
remove_action( 'wp_footer',          'wp_enqueue_global_styles', 1 );
remove_action( 'wp_body_open',       'wp_global_styles_render_svg_filters' );

// ── Remove emoji scripts/styles ───────────────────────────────────────────────
remove_action( 'wp_head',             'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles',     'print_emoji_styles' );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'admin_print_styles',  'print_emoji_styles' );
remove_filter( 'the_content_feed',    'wp_staticize_emoji' );
remove_filter( 'comment_text_rss',    'wp_staticize_emoji' );
remove_filter( 'wp_mail',             'wp_staticize_emoji_for_email' );
add_filter( 'tiny_mce_plugins', fn( $plugins ) => array_diff( $plugins, [ 'wpemoji' ] ) );
add_filter( 'wp_resource_hints', function ( array $hints, string $relation_type ): array {
	if ( $relation_type !== 'dns-prefetch' ) return $hints;
	return array_filter( $hints, fn( $hint ) => ! str_contains( $hint['href'] ?? $hint, 'emoji' ) );
}, 10, 2 );

add_action( 'after_setup_theme', function () {

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', [
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
		'style',
		'script',
	] );

	register_nav_menus( [
		'primary' => __( 'Primary Menu', 'matize' ),
		'footer'  => __( 'Footer Menu', 'matize' ),
	] );

} );
