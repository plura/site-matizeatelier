<?php

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
