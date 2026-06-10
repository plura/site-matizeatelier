<?php

// ── Per-page background colour ────────────────────────────────────────────────
//
// ACF select field 'mtz_page_bg' on pages/CPTs. Choices are the six brand
// palette tokens. Outputs an inline <style> on wp_head that overrides
// --mtz-color-bg on <body>. Charcoal also flips the text/muted/border tokens
// (same pattern as the footer dark context) so copy stays readable.

add_action( 'wp_head', function (): void {

	if ( ! is_singular() ) return;

	$color = get_field( 'mtz_page_bg' );

	if ( ! $color || $color === 'offwhite' ) return;

	if ( $color === 'charcoal' ) {
		// Dark background — flip all semantic tokens like the footer/header-over-hero.
		echo '<style>body{--mtz-color-bg:var(--mtz-color-charcoal);--mtz-color-text:var(--mtz-color-offwhite);--mtz-color-muted:var(--mtz-color-offwhite-60);--mtz-color-border:var(--mtz-color-offwhite-30);background-color:var(--mtz-color-bg);color:var(--mtz-color-text)}</style>';
		return;
	}

	// Accent colours (coral/sage/gold/teal) — charcoal text reads fine on all of them.
	echo '<style>body{--mtz-color-bg:var(--mtz-color-' . esc_attr( $color ) . ');background-color:var(--mtz-color-bg)}</style>';

} );


// ── ACF field group registration ──────────────────────────────────────────────

add_action( 'acf/init', function (): void {

	if ( ! function_exists( 'acf_add_local_field_group' ) ) return;

	acf_add_local_field_group( [
		'key'    => 'group_mtz_page_settings',
		'title'  => 'Page Settings',
		'fields' => [
			[
				'key'           => 'field_mtz_page_bg',
				'label'         => 'Background Colour',
				'name'          => 'mtz_page_bg',
				'type'          => 'select',
				'default_value' => 'offwhite',
				'allow_null'    => 0,
				'choices'       => [
					'offwhite' => 'Off-white',
					'coral'    => 'Coral',
					'sage'     => 'Sage',
					'gold'     => 'Gold',
					'teal'     => 'Teal',
					'charcoal' => 'Preto',
				],
				'ui'            => 1,
			],
		],
		'location' => [
			[ [ 'param' => 'post_type', 'operator' => '==', 'value' => 'page' ] ],
			[ [ 'param' => 'post_type', 'operator' => '==', 'value' => 'mtz_service' ] ],
		],
		'menu_order'  => 100,
		'position'    => 'side',
		'style'       => 'seamless',
	] );

} );
