<?php
/**
 * ACF options page registration.
 * Field groups are defined in theme/acf-json/ and loaded automatically by ACF.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'plugins_loaded', function () {
	if ( ! function_exists( 'acf_add_options_page' ) ) return;

	acf_add_options_page( [
		'page_title' => __( 'Theme Settings', 'matize' ),
		'menu_title' => __( 'Theme Settings', 'matize' ),
		'menu_slug'  => 'matize-theme-settings',
		'capability' => 'edit_theme_options',
		'redirect'   => false,
	] );
} );
