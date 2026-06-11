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
