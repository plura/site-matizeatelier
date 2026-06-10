<?php

// ── Per-page background colour ────────────────────────────────────────────────
//
// ACF select field 'mtz_page_bg' — field group defined in theme/acf-json/.
// Outputs a data-mtz-bg attribute on <body> (called from header.php).
// CSS in base.css handles all visual consequences via body[data-mtz-bg="…"].

function mtz_body_bg_attr(): void {
	if ( ! is_singular() ) return;
	$color = get_field( 'mtz_page_bg' );
	if ( ! $color || $color === 'offwhite' ) return;
	echo ' data-mtz-bg="' . esc_attr( $color ) . '"';
}
