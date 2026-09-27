<?php

// ── Home services: stacked editorial list (strip header + content grid) ───────
// Reshapes the flat plura_wp_post content into two blocks per the 2026-07
// redesign: a strip header ("01 — Title" + tagline) and a content grid
// (outlined number, title/excerpt, tilted photo). WP loop/hooks unchanged —
// only the inner presentation markup.
add_filter( 'plura_wp_post', function ( array $content, WP_Post $post, ?string $context, ?int $index ): array {
	if ( $context !== 'home-services' || $post->post_type !== 'mtz_service' ) {
		return $content;
	}

	$number  = str_pad( (string) ( ( $index ?? 0 ) + 1 ), 2, '0', STR_PAD_LEFT );
	$tagline = get_field( 'mtz_service_tagline', $post->ID );
	$excerpt = get_field( 'mtz_service_excerpt', $post->ID );

	return [
		'strip' => sprintf(
			'<div class="home-services__strip"><span class="home-services__strip-title">%1$s — %2$s</span>%3$s</div>',
			esc_html( $number ),
			esc_html( get_the_title( $post ) ),
			$tagline ? '<span class="home-services__strip-tagline">' . esc_html( $tagline ) . '</span>' : ''
		),
		// NOT named 'content' — plura_wp_post() unconditionally unsets that key
		// after running this filter (see plugin/src/includes/core/wp-posts.php:
		// $ordered_content is reassigned to $filtered_content, then compared
		// against ITSELF, so the "only unset if nothing changed" guard always
		// fires). Any filter returning a 'content' key loses it silently.
		'grid' => sprintf(
			'<div class="home-services__content"><div class="home-services__number">%1$s</div><div class="home-services__text">%2$s%3$s</div>%4$s</div>',
			esc_html( $number ),
			$content['title'] ?? '',
			$excerpt ? '<div class="home-services__excerpt">' . esc_html( $excerpt ) . '</div>' : '',
			! empty( $content['featured-image'] ) ? '<div class="home-services__photo">' . $content['featured-image'] . '</div>' : ''
		),
	];
}, 10, 4 );

// ── Home services: per-card accent tint ─────────────────────────────────────
// Same accent as the service's section on page-services.php.
add_filter( 'plura_wp_post_atts', function ( array $atts, WP_Post $post, ?string $context ): array {
	if ( $context !== 'home-services' || $post->post_type !== 'mtz_service' ) {
		return $atts;
	}

	$atts['data-service-accent'] = mtz_post_accent( $post->ID );

	return $atts;
}, 10, 3 );
