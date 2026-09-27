<?php

add_action( 'wp_enqueue_scripts', function () {

	$dir = get_template_directory();

	$scripts = [
		// GSAP (CDN)
		'https://cdn.jsdelivr.net/npm/gsap@3.15/dist/gsap.min.js'          => [ 'handle' => 'gsap' ],
		'https://cdn.jsdelivr.net/npm/gsap@3.15/dist/ScrollTrigger.min.js' => [ 'handle' => 'gsap-scrolltrigger', 'deps' => [ 'matize-gsap' ] ],
		'https://cdn.jsdelivr.net/npm/gsap@3.15/dist/SplitText.min.js'     => [ 'handle' => 'gsap-splittext',     'deps' => [ 'matize-gsap' ] ],

		// Theme CSS — each file enqueued individually so filemtime() cache-busts correctly
		"$dir/assets/css/base.css",
		"$dir/assets/css/layout.css",
		"$dir/assets/css/components.css",
		"$dir/assets/css/forms.css",

		// Theme JS
		"$dir/assets/js/main.js" => [ 'module' => true ],
	];

	// Page-specific CSS
	$page_css = [
		"$dir/assets/css/pages/home.css"     => is_front_page(),
		"$dir/assets/css/pages/services.css" => is_page_template( 'page-services.php' ),
		"$dir/assets/css/pages/about.css"    => is_page_template( 'page-about.php' ),
		"$dir/assets/css/pages/gallery.css"  => is_page_template( 'page-gallery.php' ),
		"$dir/assets/css/pages/contact.css"  => is_page_template( 'page-contact.php' ),
	];

	foreach ( $page_css as $file => $condition ) {
		if ( $condition ) {
			$scripts[] = $file;
		}
	}

	// Gallery: Fancybox lightbox (CDN, only on gallery page)
	if ( is_page_template( 'page-gallery.php' ) ) {
		$scripts['https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.1/dist/fancybox/fancybox.css'] = [ 'handle' => 'fancybox' ];
		$scripts['https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.1/dist/fancybox/fancybox.umd.js'] = [ 'handle' => 'fancybox-js' ];
	}

	plura_wp_enqueue( scripts: $scripts, cache: true, prefix: 'matize-', admin: false );

	// plura_wp_enqueue() prints every script in <head>, render-blocking. Deferred
	// classic scripts still run in document order before the (deferred) main.js
	// module, so every module keeps seeing gsap/ScrollTrigger/Fancybox as globals.
	foreach ( [ 'matize-gsap', 'matize-gsap-scrolltrigger', 'matize-gsap-splittext', 'matize-fancybox-js' ] as $handle ) {
		wp_script_add_data( $handle, 'strategy', 'defer' );
	}

} );

// Preload the two cuts every page renders above the fold (body + titles), so
// they don't wait for base.css to be parsed. Keep in sync with its @font-face.
add_filter( 'wp_preload_resources', function ( array $resources ): array {
	foreach ( [ 'vinila-regular', 'vinila-extended-bold' ] as $font ) {
		$resources[] = [
			'href'        => get_template_directory_uri() . "/assets/fonts/vinila/{$font}.woff2",
			'as'          => 'font',
			'type'        => 'font/woff2',
			'crossorigin' => 'anonymous',
		];
	}
	return $resources;
} );

// Deregister jQuery on the frontend — ACF Free and WPML don't need it there.
// Remove this filter if a plugin requires it.
add_action( 'wp_enqueue_scripts', function () {
	wp_deregister_script( 'jquery' );
}, 100 );

// Dequeue unused WPML assets.
add_action( 'wp_enqueue_scripts', function () {
	// Styles a hidden legacy horizontal switcher — never rendered.
	wp_dequeue_style( 'wpml-legacy-horizontal-list-0' );
	wp_deregister_style( 'wpml-legacy-horizontal-list-0' );

	// Sets a language cookie — redundant with directory-based URLs (/en/).
	// Uncomment only after confirming browser-language redirect and
	// "remember visitor's language" are both disabled in WPML → Languages,
	// AND the contact form sends its language explicitly — its AJAX handler
	// currently gets the language (email template, messages) from this cookie.
	// wp_dequeue_script( 'wpml-cookie' );
	// wp_deregister_script( 'wpml-cookie' );
}, 100 );
