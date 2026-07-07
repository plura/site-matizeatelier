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
		$inner = plura_img2svg( plura_wp_image( $logo_id, 'full', [ 'class' => 'site-logo__img' ] ) );
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
 * Renders a gallery cluster — 2-col grid of small images.
 * Returns empty string if no images are provided.
 *
 * @param array $images  ACF gallery array (each item has at least 'ID').
 * @param int   $max     Maximum number of images to show. Default 3.
 * @return string
 */
function mtz_gallery_cluster( array $images, int $max = 3 ): string {
	if ( ! $images ) return '';

	$imgs = '';
	foreach ( array_slice( $images, 0, $max ) as $img ) {
		$imgs .= plura_wp_image( $img['ID'], 'medium', [ 'class' => 'gallery-cluster__img' ] );
	}

	return '<div class="gallery-cluster">' . $imgs . '</div>';
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
			plura_wp_image( $image['ID'], 'large' )
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
