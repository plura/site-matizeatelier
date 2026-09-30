<?php

if ( ! defined( 'ABSPATH' ) ) exit;

const MTZ_IMAGE_COLOR_META_KEY = 'mtz_image_color_detected';
const MTZ_IMAGE_COLOR_VERSION_KEY = 'mtz_image_color_version';
const MTZ_IMAGE_COLOR_VERSION = '1';

function mtz_image_color_labels(): array {
	return [
		'red'     => __( 'Red', 'matize' ),
		'orange'  => __( 'Orange', 'matize' ),
		'yellow'  => __( 'Yellow', 'matize' ),
		'green'   => __( 'Green', 'matize' ),
		'blue'    => __( 'Blue', 'matize' ),
		'purple'  => __( 'Purple', 'matize' ),
		'pink'    => __( 'Pink', 'matize' ),
		'brown'   => __( 'Brown', 'matize' ),
		'neutral' => __( 'Neutral', 'matize' ),
	];
}

function mtz_detect_image_color( int $attachment_id ): string|WP_Error {
	$file = get_attached_file( $attachment_id );
	if ( ! $file || ! is_file( $file ) ) {
		return new WP_Error( 'mtz_image_color_missing_file', __( 'The image file could not be found.', 'matize' ) );
	}

	$editor = wp_get_image_editor( $file );
	if ( is_wp_error( $editor ) ) {
		return $editor;
	}

	// resize() refuses to upscale and errors on images already this small, so analyse those as-is.
	$size = $editor->get_size();
	if ( $size['width'] > 40 || $size['height'] > 40 ) {
		$resized = $editor->resize( 40, 40, false );
		if ( is_wp_error( $resized ) ) {
			return $resized;
		}
		$size = $editor->get_size();
	}

	$image  = $editor->get_image();
	$counts = array_fill_keys( array_keys( mtz_image_color_labels() ), 0 );
	$width  = $size['width'];
	$height = $size['height'];

	if ( class_exists( 'Imagick' ) && $image instanceof Imagick ) {
		// CMYK pixels would otherwise be read as if their channels were RGB.
		if ( Imagick::COLORSPACE_CMYK === $image->getImageColorspace() ) {
			$image->transformImageColorspace( Imagick::COLORSPACE_SRGB );
		}
		for ( $y = 0; $y < $height; $y++ ) {
			for ( $x = 0; $x < $width; $x++ ) {
				$pixel = $image->getImagePixelColor( $x, $y )->getColor( true );
				// Same cut-off as the GD branch (alpha > 120 of 127).
				if ( $pixel['a'] < 0.06 ) {
					continue;
				}
				$color = mtz_image_color_from_rgb(
					(int) round( $pixel['r'] * 255 ),
					(int) round( $pixel['g'] * 255 ),
					(int) round( $pixel['b'] * 255 )
				);
				$counts[ $color ]++;
			}
		}
	} elseif ( function_exists( 'imagecolorat' ) && ( is_resource( $image ) || is_object( $image ) ) ) {
		for ( $y = 0; $y < $height; $y++ ) {
			for ( $x = 0; $x < $width; $x++ ) {
				$pixel = imagecolorsforindex( $image, imagecolorat( $image, $x, $y ) );
				if ( isset( $pixel['alpha'] ) && $pixel['alpha'] > 120 ) {
					continue;
				}
				$color = mtz_image_color_from_rgb( $pixel['red'], $pixel['green'], $pixel['blue'] );
				$counts[ $color ]++;
			}
		}
	} else {
		return new WP_Error( 'mtz_image_color_unsupported_editor', __( 'The active image editor does not support color analysis.', 'matize' ) );
	}

	arsort( $counts );
	$color = array_key_first( $counts );
	if ( ! $color || $counts[ $color ] === 0 ) {
		return new WP_Error( 'mtz_image_color_no_pixels', __( 'No visible pixels could be analyzed.', 'matize' ) );
	}

	return $color;
}

function mtz_image_color_from_rgb( int $red, int $green, int $blue ): string {
	$max        = max( $red, $green, $blue );
	$min        = min( $red, $green, $blue );
	$brightness = $max / 255;
	$saturation = $max === 0 ? 0 : ( $max - $min ) / $max;

	if ( $brightness < 0.18 || $saturation < 0.18 ) {
		return 'neutral';
	}

	if ( $max === $red ) {
		$hue = 60 * ( ( $green - $blue ) / ( $max - $min ) );
	} elseif ( $max === $green ) {
		$hue = 60 * ( 2 + ( $blue - $red ) / ( $max - $min ) );
	} else {
		$hue = 60 * ( 4 + ( $red - $green ) / ( $max - $min ) );
	}
	$hue = fmod( $hue + 360, 360 );

	if ( $hue < 15 || $hue >= 345 ) return 'red';
	if ( $hue < 40 && $brightness < 0.55 ) return 'brown';
	if ( $hue < 40 ) return 'orange';
	if ( $hue < 70 ) return 'yellow';
	if ( $hue < 165 ) return 'green';
	if ( $hue < 250 ) return 'blue';
	if ( $hue < 290 ) return 'purple';
	return 'pink';
}

/**
 * Effective color of an image: the editor's override if set, otherwise the detected one.
 *
 * @param int $attachment_id Attachment ID.
 * @return string Color slug from mtz_image_color_labels(), or '' when unclassified.
 */
function mtz_get_image_color( int $attachment_id ): string {
	$labels   = mtz_image_color_labels();
	$override = get_post_meta( $attachment_id, 'mtz_image_color_override', true );
	if ( is_string( $override ) && isset( $labels[ $override ] ) ) {
		return $override;
	}

	$detected = get_post_meta( $attachment_id, MTZ_IMAGE_COLOR_META_KEY, true );
	return is_string( $detected ) && isset( $labels[ $detected ] ) ? $detected : '';
}

add_filter( 'wp_generate_attachment_metadata', function ( $metadata, $attachment_id ) {
	if ( ! wp_attachment_is_image( $attachment_id ) ) {
		return $metadata;
	}

	$color = mtz_detect_image_color( (int) $attachment_id );
	if ( is_wp_error( $color ) ) {
		error_log( sprintf( 'Matize image color detection failed for attachment %d: %s', $attachment_id, $color->get_error_message() ) );
		return $metadata;
	}

	update_post_meta( $attachment_id, MTZ_IMAGE_COLOR_META_KEY, $color );
	update_post_meta( $attachment_id, MTZ_IMAGE_COLOR_VERSION_KEY, MTZ_IMAGE_COLOR_VERSION );
	return $metadata;
}, 10, 2 );
