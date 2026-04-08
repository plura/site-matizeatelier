<?php

// Local JSON — save and load field groups from /acf-json/
add_filter( 'acf/settings/save_json', function () {
	return get_template_directory() . '/acf-json';
} );

add_filter( 'acf/settings/load_json', function ( array $paths ): array {
	$paths[] = get_template_directory() . '/acf-json';
	return $paths;
} );

// Register Theme Settings options page
if ( function_exists( 'acf_add_options_page' ) ) {
	acf_add_options_page( [
		'page_title' => __( 'Theme Settings', 'matize' ),
		'menu_title' => __( 'Theme Settings', 'matize' ),
		'menu_slug'  => 'matize-theme-settings',
		'capability' => 'edit_theme_options',
		'redirect'   => false,
	] );
}

// Field groups are registered in /includes/fields/
foreach ( glob( get_template_directory() . '/includes/fields/*.php' ) as $file ) {
	require_once $file;
}
