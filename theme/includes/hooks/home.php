<?php

// ─── Home — Services grid ─────────────────────────────────────────────────────

add_filter( 'plura_wp_post', function ( array $entry, WP_Post $post, ?string $context, array $original ): array {

	if ( $context !== 'home-services' ) return $entry;

	$a = [];

	foreach ( [ 'featured-image', 'title' ] as $key ) {
		if ( array_key_exists( $key, $original ) ) {
			$a[ $key ] = $original[ $key ];
		}
	}

	$desc = get_field( 'mtz_service_description', $post->ID );
	if ( $desc ) {
		$a['description'] = '<div class="home-services__desc">' . wp_kses_post( $desc ) . '</div>';
	}

	return $a;

}, 10, 4 );

