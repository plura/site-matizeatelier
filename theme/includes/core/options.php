<?php
/**
 * Global ACF options helper.
 *
 * mtz_option( $key ) fetches an ACF options field once per request and
 * caches the result statically — subsequent calls return the cached value.
 *
 * Usage: mtz_option( 'mtz_contact' ), mtz_option( 'mtz_social' )
 */

if ( ! defined( 'ABSPATH' ) ) exit;

function mtz_option( string $key ): array {
	static $cache = [];

	if ( ! isset( $cache[ $key ] ) ) {
		$cache[ $key ] = function_exists( 'get_field' )
			? ( get_field( $key, 'option' ) ?: [] )
			: [];
	}

	return $cache[ $key ];
}
