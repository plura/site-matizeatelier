<?php

add_action( 'wp_enqueue_scripts', function () {

	$dir = get_template_directory();

	$scripts = [
		// GSAP (CDN)
		'https://cdn.jsdelivr.net/npm/gsap@3/dist/gsap.min.js'          => [ 'handle' => 'gsap' ],
		'https://cdn.jsdelivr.net/npm/gsap@3/dist/ScrollTrigger.min.js' => [ 'handle' => 'gsap-scrolltrigger', 'deps' => [ 'matize-gsap' ] ],

		// Lucide icons (CDN)
		'https://unpkg.com/lucide@latest/dist/umd/lucide.min.js' => [ 'handle' => 'lucide' ],

		// Theme assets — resolves to assets/css/main.css + assets/js/main.js
		"$dir/assets/%s/main.%s",
	];

	// Page-specific CSS
	$page_css = [
		"$dir/assets/css/pages/home.css"     => is_front_page(),
		"$dir/assets/css/pages/services.css" => is_page_template( 'page-services.php' ) || is_post_type_archive( 'mtz_service' ),
		"$dir/assets/css/pages/about.css"    => is_page_template( 'page-about.php' ),
		"$dir/assets/css/pages/gallery.css"  => is_page_template( 'page-gallery.php' ),
		"$dir/assets/css/pages/contact.css"  => is_page_template( 'page-contact.php' ),
	];

	foreach ( $page_css as $file => $condition ) {
		if ( $condition ) {
			$scripts[] = $file;
		}
	}

	plura_wp_enqueue( scripts: $scripts, cache: false, prefix: 'matize-', admin: false );

} );

// Deregister jQuery on the frontend — ACF Free and WPML don't need it there.
// Remove this filter if a plugin requires it.
add_action( 'wp_enqueue_scripts', function () {
	wp_deregister_script( 'jquery' );
}, 100 );
