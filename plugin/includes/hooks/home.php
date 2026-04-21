<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// ─── Home — Services grid ─────────────────────────────────────────────────────

add_filter( 'plura_wp_post', function ( array $entry, WP_Post $post, ?string $context, ?int $index, array $original ): array {

	if ( $context !== 'home-services' ) return $entry;

	$output = [];

	foreach ( [ 'featured-image', 'title' ] as $key ) {
		if ( array_key_exists( $key, $original ) ) {
			$output[ $key ] = $original[ $key ];
		}
	}

	$excerpt = get_field( 'mtz_service_excerpt', $post->ID );
	if ( $excerpt ) {
		$output['excerpt'] = '<div class="home-services__excerpt">' . esc_html( $excerpt ) . '</div>';
	}

	return $output;

}, 10, 5 );

