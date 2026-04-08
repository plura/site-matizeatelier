<?php

// Local JSON — ensure saves go to /acf-json/ (load path is the default)
add_filter( 'acf/settings/save_json', function () {
	return get_template_directory() . '/acf-json';
} );

// Register Theme Settings options page — priority 20 so textdomain is already loaded
add_action( 'after_setup_theme', function () {
	if ( function_exists( 'acf_add_options_page' ) ) {
		acf_add_options_page( [
			'page_title' => __( 'Theme Settings', 'matize' ),
			'menu_title' => __( 'Theme Settings', 'matize' ),
			'menu_slug'  => 'matize-theme-settings',
			'capability' => 'edit_theme_options',
			'redirect'   => false,
		] );
	}
}, 20 );

// Field groups are registered in /includes/fields/
foreach ( glob( get_template_directory() . '/includes/fields/*.php' ) as $file ) {
	require_once $file;
}
