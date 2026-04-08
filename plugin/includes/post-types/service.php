<?php

add_action( 'init', function () {

	register_post_type( 'mtz_service', [
		'label'               => __( 'Services', 'matize' ),
		'labels'              => [
			'name'               => __( 'Services', 'matize' ),
			'singular_name'      => __( 'Service', 'matize' ),
			'add_new'            => __( 'Add New', 'matize' ),
			'add_new_item'       => __( 'Add New Service', 'matize' ),
			'edit_item'          => __( 'Edit Service', 'matize' ),
			'new_item'           => __( 'New Service', 'matize' ),
			'view_item'          => __( 'View Service', 'matize' ),
			'search_items'       => __( 'Search Services', 'matize' ),
			'not_found'          => __( 'No services found', 'matize' ),
			'not_found_in_trash' => __( 'No services found in trash', 'matize' ),
		],
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_rest'        => false,
		'menu_icon'           => 'dashicons-clipboard',
		'supports'            => [ 'title', 'thumbnail', 'page-attributes' ],
		'has_archive'         => 'services',
		'rewrite'             => [
			'slug'       => 'service',
			'with_front' => false,
		],
	] );

} );
