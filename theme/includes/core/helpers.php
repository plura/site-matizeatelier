<?php

/**
 * Renders the site logo as an inline SVG link.
 * Always outputs .site-logo and .site-logo__img for component styling.
 * Falls back to the site name if no custom logo is set.
 *
 * @param string $extra_class  Optional extra class on the <a> tag (e.g. 'site-header__logo').
 * @return string
 */
function mtz_logo( string $extra_class = '' ): string {
	$logo_id = get_theme_mod( 'custom_logo' );
	$a_class = trim( 'site-logo ' . $extra_class );

	if ( $logo_id ) {
		$inner = plura_img2svg( html: plura_wp_image( attachment: $logo_id, size: 'full', atts: [ 'class' => 'site-logo__img' ] ) ?? '' );
	} else {
		$inner = esc_html( get_bloginfo( 'name' ) );
	}

	return sprintf(
		'<a href="%s" class="%s" aria-label="%s">%s</a>',
		esc_url( home_url( '/' ) ),
		esc_attr( $a_class ),
		esc_attr( get_bloginfo( 'name' ) ),
		$inner
	);
}

/**
 * Extracts a social media username from a profile URL.
 * Returns the last non-empty path segment prefixed with @.
 * e.g. https://www.instagram.com/matize.atelier/ → @matize.atelier
 *      https://www.linkedin.com/in/username/     → @username
 *
 * @param string $url  Full profile URL.
 * @return string
 */
function mtz_social_username( string $url ): string {
	if ( ! $url ) return '';
	$path     = parse_url( $url, PHP_URL_PATH ) ?? '';
	$username = basename( rtrim( $path, '/' ) );
	return $username ? '@' . $username : '';
}

/**
 * Returns an inline Lucide icon (v1.48.0, ISC licence) as decorative SVG.
 * Inlined server-side instead of loading the Lucide library: the site uses
 * only these few icons, and inline markup renders with no post-load swap.
 *
 * @param string $name  Icon name, e.g. 'menu' — a key of the map below.
 * @return string       SVG markup, or '' for an unknown name.
 */
function mtz_icon( string $name ): string {
	$icons = [
		'chevron-down' => '<path d="m6 9 6 6 6-6"/>',
		'mail'         => '<path d="m22 7-8.991 5.727a2 2 0 0 1-2.009 0L2 7"/><rect x="2" y="4" width="20" height="16" rx="2"/>',
		'map-pin'      => '<path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle cx="12" cy="10" r="3"/>',
		'menu'         => '<path d="M4 5h16"/><path d="M4 12h16"/><path d="M4 19h16"/>',
		'phone'        => '<path d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384"/>',
		'send'         => '<path d="M14.536 21.686a.5.5 0 0 0 .937-.024l6.5-19a.496.496 0 0 0-.635-.635l-19 6.5a.5.5 0 0 0-.024.937l7.93 3.18a2 2 0 0 1 1.112 1.11z"/><path d="m21.854 2.147-10.94 10.939"/>',
		'x'            => '<path d="M18 6 6 18"/><path d="m6 6 12 12"/>',
	];

	if ( ! isset( $icons[ $name ] ) ) return '';

	return sprintf(
		'<svg class="lucide lucide-%s" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">%s</svg>',
		esc_attr( $name ),
		$icons[ $name ]
	);
}

/**
 * Renders a fanned image stack — 2 decorative ghost cards + up to 3 real
 * image cards. Falls back to $fallback_id (e.g. a featured image) when
 * $images is empty. Returns empty string if no image source is available.
 *
 * Card position depends on count, so a lone image lands on the flat,
 * unrotated centre card rather than a rotated corner:
 *   1 image  → mid
 *   2 images → back, front
 *   3 images → back, mid, front
 *
 * @param array    $images       ACF gallery array (each item has at least 'ID').
 * @param int|null $fallback_id  Attachment ID used when $images is empty.
 * @return string
 */
function mtz_img_stack( array $images, ?int $fallback_id = null ): string {
	if ( ! $images && $fallback_id ) {
		$images = [ [ 'ID' => $fallback_id ] ];
	}
	if ( ! $images ) return '';

	$position_sets = [
		1 => [ 'mid' ],
		2 => [ 'back', 'front' ],
		3 => [ 'back', 'mid', 'front' ],
	];

	$images    = array_slice( $images, 0, 3 );
	$positions = $position_sets[ count( $images ) ];

	$cards = '';
	foreach ( $images as $i => $image ) {
		$cards .= sprintf(
			'<div class="img-card img-card--%s">%s</div>',
			esc_attr( $positions[ $i ] ),
			plura_wp_image( attachment: $image['ID'], size: 'large' )
		);
	}

	return '<div class="content-section__media">'
		. sprintf( '<div class="img-stack img-stack--%d">', count( $images ) )
		. '<div class="img-ghost img-ghost--1"></div>'
		. '<div class="img-ghost img-ghost--2"></div>'
		. $cards
		. '</div>'
		. '</div>';
}

/**
 * Renders a decorative furniture line-art SVG, inlined so its stroke
 * (currentColor) can pick up the wrapper's CSS `color`. Base positioning
 * (absolute, colour, size) lives in .bg-vector (components.css); pass
 * $class to add a page-specific placement class (left/top/rotate/scale/
 * opacity) defined in the relevant pages/*.css file.
 *
 * @param string $name   SVG filename without extension (e.g. 'furniture-armchair').
 * @param string $class  Optional placement class, e.g. 'bg-vector--mission-1'.
 * @return string
 */
function mtz_bg_vector( string $name, string $class = '' ): string {
	$path = get_template_directory() . "/assets/svgs/{$name}.svg";
	if ( ! file_exists( $path ) ) return '';

	return sprintf(
		'<div class="%s" aria-hidden="true">%s</div>',
		esc_attr( trim( 'bg-vector ' . $class ) ),
		file_get_contents( $path )
	);
}
