<?php

// ── Service URLs: a section on the Services page, not a single page ───────────
// Services have no single template (index.php is empty), so every service link
// targets its section on the Services page (id = post slug, see page-services.php).

/**
 * Returns the URL of the page using the Services template, in the current
 * language. Cached per request.
 *
 * @return string  Page URL, or '' if no page uses the template.
 */
function mtz_services_page_url(): string {
	static $url = null;

	if ( $url === null ) {
		$ids = get_posts( [
			'post_type'        => 'page',
			'meta_key'         => '_wp_page_template',
			'meta_value'       => 'page-services.php',
			'posts_per_page'   => 1,
			'fields'           => 'ids',
			'suppress_filters' => false, // lets WPML scope the query to the current language
		] );
		$url = $ids ? ( get_permalink( apply_filters( 'wpml_object_id', $ids[0], 'page', true ) ) ?: '' ) : '';
	}

	return $url;
}

/**
 * Returns a service's URL: its section on the Services page.
 *
 * @param int $post_id  Service post ID.
 * @return string       Services page URL + '#<slug>', or '' without a Services page.
 */
function mtz_service_url( int $post_id ): string {
	$page = mtz_services_page_url();
	return $page ? $page . '#' . get_post_field( 'post_name', $post_id ) : '';
}

// Home service cards (link: 1 — the whole card is the <a>) → the service's section.
add_filter( 'plura_wp_link_atts', function ( array $link_atts, WP_Post|WP_Term|string $target, ?string $context ): array {
	if ( $context !== 'home-services' || ! ( $target instanceof WP_Post ) || $target->post_type !== 'mtz_service' ) {
		return $link_atts;
	}
	$url = mtz_service_url( $target->ID );
	if ( $url ) $link_atts['href'] = $url;
	return $link_atts;
}, 10, 3 );

// Old /service/<slug>/ URLs (bookmarks, search results) → the service's section…
add_action( 'template_redirect', function () {
	if ( ! is_singular( 'mtz_service' ) ) return;
	$url = mtz_service_url( get_queried_object_id() );
	if ( $url ) {
		wp_safe_redirect( $url, 301 );
		exit;
	}
} );

// …and keep those redirecting URLs out of the Rank Math sitemap.
add_filter( 'rank_math/sitemap/exclude_post_type', fn( $exclude, $type ) => $type === 'mtz_service' ? true : $exclude, 10, 2 );

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
