<?php

// ── Strip all content except logo + name ─────────────────────────────────────
add_filter( 'plura_wp_post', function ( array $content, WP_Post $post, ?string $context ): array {
	if ( $context === 'brands-grid' && $post->post_type === 'mtz_brand' ) {
		return array_intersect_key( $content, array_flip( [ 'featured-image', 'title' ] ) );
	}
	return $content;
}, 10, 3 );

// ── Override link to mtz_brand_url; fallback to # if unset ───────────────────
add_filter( 'plura_wp_link_atts', function ( array $link_atts, WP_Post|WP_Term|string $target, ?string $context ): array {
	if ( $context !== 'brands-grid' || ! ( $target instanceof WP_Post ) || $target->post_type !== 'mtz_brand' ) {
		return $link_atts;
	}
	$url = get_field( 'mtz_brand_url', $target->ID );
	$link_atts['href'] = $url ?: '#';
	return $link_atts;
}, 10, 3 );
