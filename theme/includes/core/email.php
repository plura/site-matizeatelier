<?php
/**
 * Bespoke email templates — hooks into the plugin's mtz_email_template
 * filter to serve the theme's on-brand contact templates instead of the
 * plugin's field-agnostic fallback. Source: /mail-templates (MJML, not
 * deployed); built HTML lives in theme/templates/contact/.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Maps a language code to a contact template language. WPML's Portuguese code is "pt-pt".
 *
 * @param string|null $lang Language code.
 * @return string "pt" for pt, pt-pt or pt_PT; "en" for anything else.
 */
function mtz_email_lang( ?string $lang ): string {
	return in_array( strtolower( str_replace( '_', '-', (string) $lang ) ), [ 'pt', 'pt-pt' ], true ) ? 'pt' : 'en';
}

add_filter( 'mtz_email_template', function ( string $default, string $form_name, bool $is_reply ): string {
	// Only one form on the site right now (contact) — once a second, differently
	// shaped form exists, branch on $form_name here to pick its own template set.
	// Prefer the page's language, since WPML's value in admin-ajax comes from a cookie.
	$posted = $_POST['mtz_lang'] ?? null;
	$lang   = mtz_email_lang( is_string( $posted ) ? $posted : apply_filters( 'wpml_current_language', null ) );

	$file = $is_reply ? "contact-reply-{$lang}.html" : "contact-{$lang}.html";

	return get_template_directory() . '/templates/contact/' . $file;
}, 10, 3 );
