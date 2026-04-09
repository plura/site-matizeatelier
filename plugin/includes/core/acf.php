<?php
/**
 * ACF configuration.
 * - JSON save/load paths point to plugin/acf-json/
 * - Options page registration
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$acf_json_path = plugin_dir_path( dirname( __DIR__ ) ) . 'acf-json';

// Save field group JSON to plugin
add_filter( 'acf/settings/save_json', fn() => $acf_json_path );

// Load field group JSON from plugin
add_filter( 'acf/settings/load_json', function ( array $paths ) use ( $acf_json_path ): array {
	$paths[] = $acf_json_path;
	return $paths;
} );

// Options page
add_action( 'init', function () {
	if ( ! function_exists( 'acf_add_options_page' ) ) return;

	acf_add_options_page( [
		'page_title' => __( 'Theme Settings', 'matize' ),
		'menu_title' => __( 'Theme Settings', 'matize' ),
		'menu_slug'  => 'matize-theme-settings',
		'capability' => 'edit_theme_options',
		'redirect'   => false,
	] );
} );
