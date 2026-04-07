<?php

add_action( 'wp_enqueue_scripts', function () {

	plura_wp_enqueue( [
		// GSAP (CDN)
		'https://cdn.jsdelivr.net/npm/gsap@3/dist/gsap.min.js',
		'https://cdn.jsdelivr.net/npm/gsap@3/dist/ScrollTrigger.min.js' => [
			'deps' => [ 'gsap' ],
		],

		// Theme assets — pattern resolves to assets/css/main.css + assets/js/main.js
		get_template_directory() . '/assets/%s/main.%s',
	], true, 'matize-', false );

} );

// Deregister jQuery on the frontend — ACF Free and WPML don't need it there.
// Remove this filter if a plugin requires it.
add_action( 'wp_enqueue_scripts', function () {
	wp_deregister_script( 'jquery' );
}, 100 );
