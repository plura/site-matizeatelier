<?php

add_action( 'init', function () {

	register_post_type( 'mtz_brand', [
		'label'               => __( 'Brands', 'matize' ),
		'labels'              => [
			'name'               => __( 'Brands', 'matize' ),
			'singular_name'      => __( 'Brand', 'matize' ),
			'add_new'            => __( 'Add New', 'matize' ),
			'add_new_item'       => __( 'Add New Brand', 'matize' ),
			'edit_item'          => __( 'Edit Brand', 'matize' ),
			'new_item'           => __( 'New Brand', 'matize' ),
			'search_items'       => __( 'Search Brands', 'matize' ),
			'not_found'          => __( 'No brands found', 'matize' ),
			'not_found_in_trash' => __( 'No brands found in trash', 'matize' ),
		],
		'public'              => false,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_rest'        => false,
		'menu_icon'           => 'dashicons-store',
		'supports'            => [ 'title', 'thumbnail', 'page-attributes' ],
		'rewrite'             => false,
	] );

} );

// ─── Admin columns ────────────────────────────────────────────────────────────

add_filter( 'manage_mtz_brand_posts_columns', function ( array $columns ): array {
	$new = [];
	foreach ( $columns as $key => $label ) {
		if ( $key === 'date' ) continue; // drop date column
		$new[ $key ] = $label;
		if ( $key === 'title' ) {
			$new['mtz_brand_logo'] = __( 'Logo', 'matize' );
			$new['mtz_brand_url']  = __( 'URL', 'matize' );
		}
	}
	return $new;
} );

add_action( 'manage_mtz_brand_posts_custom_column', function ( string $column, int $post_id ): void {
	if ( $column === 'mtz_brand_logo' ) {
		$thumb = get_the_post_thumbnail( $post_id, [ 60, 40 ] );
		echo $thumb ?: '—';
	}
	if ( $column === 'mtz_brand_url' ) {
		$url = get_field( 'mtz_brand_url', $post_id );
		echo $url ? '<a href="' . esc_url( $url ) . '" target="_blank">' . esc_html( $url ) . '</a>' : '—';
	}
}, 10, 2 );
