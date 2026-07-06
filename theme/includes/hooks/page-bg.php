<?php

// ── Per-page colour theme ─────────────────────────────────────────────────────
//
// ACF select field 'mtz_page_theme' — field group defined in theme/acf-json/.
// Outputs a data-mtz-theme attribute on <body> (called from header.php).
// CSS in base.css handles all visual consequences via body[data-mtz-theme="…"].

function mtz_body_theme_attr(): void {
	if ( ! is_singular() ) return;
	$theme = get_field( 'mtz_page_theme' );
	if ( ! $theme || $theme === 'offwhite' ) return;
	echo ' data-mtz-theme="' . esc_attr( $theme ) . '"';
}

// ── Shared colour choices ─────────────────────────────────────────────────────
//
// Single source of truth for every ACF select using the brand accent palette.
// JSON field groups still ship their own 'choices' as a readable fallback,
// but acf/load_field overwrites them at runtime from here, so the palette
// only needs to be edited in one place.

function mtz_accent_choices(): array {
	return [
		'coral' => 'Coral',
		'sage'  => 'Sage',
		'gold'  => 'Gold',
		'teal'  => 'Teal',
	];
}

function mtz_inject_accent_choices( array $field ): array {
	$field['choices'] = mtz_accent_choices();
	return $field;
}

function mtz_inject_theme_choices( array $field ): array {
	$field['choices'] = [ 'offwhite' => 'Off-white' ] + mtz_accent_choices() + [ 'charcoal' => 'Preto' ];
	return $field;
}

add_filter( 'acf/load_field/key=field_mtz_page_theme', 'mtz_inject_theme_choices' );
add_filter( 'acf/load_field/key=field_mtz_about_mission_accent', 'mtz_inject_accent_choices' );
add_filter( 'acf/load_field/key=field_mtz_about_philosophy_accent', 'mtz_inject_accent_choices' );
add_filter( 'acf/load_field/key=field_mtz_about_cv_accent', 'mtz_inject_accent_choices' );
