<?php

/**
 * Renders the site logo as an inline SVG link.
 * Falls back to the site name if no custom logo is set.
 *
 * @param string $link_class  Class on the <a> tag.
 * @param string $img_class   Class on the <img> passed to plura_img2svg.
 * @return string
 */
function mtz_logo( string $link_class, string $img_class ): string {
	$logo_id = get_theme_mod( 'custom_logo' );

	if ( $logo_id ) {
		$inner = plura_img2svg( plura_wp_image( $logo_id, 'full', [ 'class' => $img_class ] ) );
	} else {
		$inner = esc_html( get_bloginfo( 'name' ) );
	}

	return sprintf(
		'<a href="%s" class="%s" aria-label="%s">%s</a>',
		esc_url( home_url( '/' ) ),
		esc_attr( $link_class ),
		esc_attr( get_bloginfo( 'name' ) ),
		$inner
	);
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
