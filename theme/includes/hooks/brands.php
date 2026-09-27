<?php

// ── Strip all content except logo + name ─────────────────────────────────────
add_filter( 'plura_wp_post', function ( array $content, WP_Post $post, ?string $context ): array {
	if ( $context === 'brands-grid' && $post->post_type === 'mtz_brand' ) {
		return array_intersect_key( $content, array_flip( [ 'featured-image', 'title' ] ) );
	}
	return $content;
}, 10, 3 );

// ── Override link to mtz_brand_url (new tab); no URL → plain, non-link <a> ────
// Dropping href (rather than '#') keeps the markup/styling but makes it
// neither focusable nor a jump-to-top link.
add_filter( 'plura_wp_link_atts', function ( array $link_atts, WP_Post|WP_Term|string $target, ?string $context ): array {
	if ( $context !== 'brands-grid' || ! ( $target instanceof WP_Post ) || $target->post_type !== 'mtz_brand' ) {
		return $link_atts;
	}
	$url = get_field( 'mtz_brand_url', $target->ID );
	if ( $url ) {
		$link_atts['href']   = $url;
		$link_atts['target'] = '_blank';
		$link_atts['rel']    = 'noopener noreferrer';
	} else {
		unset( $link_atts['href'], $link_atts['title'] );
	}
	return $link_atts;
}, 10, 3 );
