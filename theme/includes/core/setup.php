<?php

add_action( 'init', function () {
	load_theme_textdomain( 'matize', get_template_directory() . '/languages' );
} );

// ── Remove emoji scripts/styles ───────────────────────────────────────────────
remove_action( 'wp_head',             'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles',     'print_emoji_styles' );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'admin_print_styles',  'print_emoji_styles' );
remove_filter( 'the_content_feed',    'wp_staticize_emoji' );
remove_filter( 'comment_text_rss',    'wp_staticize_emoji' );
remove_filter( 'wp_mail',             'wp_staticize_emoji_for_email' );
add_filter( 'tiny_mce_plugins', fn( $plugins ) => array_diff( $plugins, [ 'wpemoji' ] ) );
add_filter( 'wp_resource_hints', fn( $hints, $relation_type ) =>
	$relation_type === 'dns-prefetch'
		? array_filter( $hints, fn( $hint ) => ! str_contains( $hint['href'] ?? $hint, 'emoji' ) )
		: $hints,
2 );

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
