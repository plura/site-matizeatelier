<?php

add_action( 'wp_enqueue_scripts', function () {

	$dir = plugin_dir_path( dirname( __DIR__ ) );

	plura_wp_enqueue(
		scripts: [ "$dir/assets/js/main.js" => [ 'module' => true ] ],
		cache:   true,
		prefix:  'matize-plugin-',
		admin:   false,
	);

	wp_localize_script( 'matize-plugin-main', 'mtzForms', [
		'ajaxUrl' => admin_url( 'admin-ajax.php' ),
		'nonce'   => wp_create_nonce( 'mtz_form' ),
	] );

} );
