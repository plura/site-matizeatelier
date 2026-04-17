<?php
/**
 * JS-facing translations.
 *
 * All translatable strings passed to JavaScript live here, grouped by feature.
 * Localized as `mtzLang` on the main plugin script.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

function mtz_js_strings(): array {
	return [
		'form' => [
			'required' => __( 'Please fill in all required fields.', 'matize' ),
			'error'    => __( 'Something went wrong. Please try again.', 'matize' ),
		],
	];
}

add_action( 'wp_enqueue_scripts', function () {
	wp_localize_script( 'matize-plugin-main', 'mtzLang', mtz_js_strings() );
} );
