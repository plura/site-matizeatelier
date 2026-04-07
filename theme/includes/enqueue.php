<?php

add_action( 'wp_enqueue_scripts', function () {

	$ver = wp_get_theme()->get( 'Version' );
	$dir = get_template_directory_uri();

	// Main stylesheet
	wp_enqueue_style( 'matize', $dir . '/assets/css/main.css', [], $ver );

	// GSAP
	wp_enqueue_script( 'gsap', 'https://cdn.jsdelivr.net/npm/gsap@3/dist/gsap.min.js', [], null, true );
	wp_enqueue_script( 'gsap-scrolltrigger', 'https://cdn.jsdelivr.net/npm/gsap@3/dist/ScrollTrigger.min.js', [ 'gsap' ], null, true );

	// Main JS
	wp_enqueue_script( 'matize', $dir . '/assets/js/main.js', [ 'gsap' ], $ver, true );

} );

// Deregister jQuery on the frontend — ACF Free and WPML don't need it there.
// Remove this filter if a plugin requires it.
add_action( 'wp_enqueue_scripts', function () {
	wp_deregister_script( 'jquery' );
}, 100 );
