<?php
/**
 * Bespoke email templates — hooks into the plugin's mtz_email_template
 * filter to serve the theme's on-brand contact templates instead of the
 * plugin's field-agnostic fallback. Source: /mail-templates (MJML, not
 * deployed); built HTML lives in theme/templates/contact/.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

add_filter( 'mtz_email_template', function ( string $default, string $form_name, bool $is_reply ): string {
	// Only one form on the site right now (contact) — once a second, differently
	// shaped form exists, branch on $form_name here to pick its own template set.
	$lang = apply_filters( 'wpml_current_language', null );
	$lang = in_array( $lang, [ 'en', 'pt' ], true ) ? $lang : 'en';

	$file = $is_reply ? "contact-reply-{$lang}.html" : "contact-{$lang}.html";

	return get_template_directory() . '/templates/contact/' . $file;
}, 10, 3 );
