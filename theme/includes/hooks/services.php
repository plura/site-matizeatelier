<?php

// ── Home services card: image + title + tagline + excerpt ─────────────────────
add_filter( 'plura_wp_post', function ( array $content, WP_Post $post, ?string $context ): array {
	if ( $context !== 'home-services' || $post->post_type !== 'mtz_service' ) {
		return $content;
	}

	$tagline = get_field( 'mtz_service_tagline', $post->ID );
	$excerpt = get_field( 'mtz_service_excerpt', $post->ID );

	return array_filter( [
		'featured-image' => $content['featured-image'] ?? '',
		'title'          => $content['title'] ?? '',
		'tagline'        => $tagline ? '<p class="plura-wp-post-tagline">' . esc_html( $tagline ) . '</p>' : '',
		'excerpt'        => $excerpt ? '<div class="plura-wp-post-excerpt">' . esc_html( $excerpt ) . '</div>' : '',
	] );
}, 10, 3 );
