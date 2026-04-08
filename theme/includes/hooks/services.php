<?php

// ─── Services — full page and archive ────────────────────────────────────────

add_filter( 'plura_wp_post', function ( array $entry, WP_Post $post, ?string $context, array $original ): array {

	if ( ! in_array( $context, [ 'page-services', 'archive-services' ], true ) ) return $entry;

	$a = [];

	foreach ( [ 'featured-image', 'title' ] as $key ) {
		if ( array_key_exists( $key, $original ) ) {
			$a[ $key ] = $original[ $key ];
		}
	}

	$desc = get_field( 'mtz_service_description', $post->ID );
	if ( $desc ) {
		$a['description'] = '<div class="services__desc">' . wp_kses_post( $desc ) . '</div>';
	}

	return $a;

}, 10, 4 );

add_filter( 'plura_wp_post_featured_image', function ( ?string $result, WP_Post $post, string $size, array $atts, ?string $context ): ?string {

	if ( ! in_array( $context, [ 'page-services', 'archive-services' ], true ) ) return $result;

	$id = get_post_thumbnail_id( $post->ID );
	if ( ! $id ) return $result;

	return plura_wp_image( attachment: $id, size: 'large', atts: $atts );

}, 10, 5 );
