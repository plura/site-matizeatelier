<?php
/**
 * Generic AJAX form handler.
 *
 * Handles form submissions via fetch(). Sanitizes fields by input type.
 * Each form passes its fields as a structured array — no hardcoded field names.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Auto-reply: sends a copy of the submission to the submitter.
// Will be replaced by a Theme Settings toggle (ACF) in a future update.
define( 'MTZ_FORM_AUTO_REPLY', true );

// ─── Localize script data ─────────────────────────────────────────────────────

add_action( 'wp_enqueue_scripts', function () {
	wp_localize_script( 'matize-plugin-main', 'mtzForms', [
		'ajaxUrl' => admin_url( 'admin-ajax.php' ),
		'nonce'   => wp_create_nonce( 'mtz_form' ),
	] );
} );

// ─── AJAX handlers ────────────────────────────────────────────────────────────

add_action( 'wp_ajax_nopriv_mtz_form', 'mtz_handle_form' );
add_action( 'wp_ajax_mtz_form',        'mtz_handle_form' );

function mtz_handle_form(): void {

	// ── Nonce ─────────────────────────────────────────────────────────────────
	if ( ! check_ajax_referer( 'mtz_form', 'nonce', false ) ) {
		wp_send_json_error( [ 'message' => __( 'Security check failed.', 'matize' ) ], 403 );
	}

	// ── Honeypot ──────────────────────────────────────────────────────────────
	if ( ! empty( $_POST['mtz_website'] ) ) {
		wp_send_json_success(); // Silent discard
	}

	// ── Sanitize fields by input type ─────────────────────────────────────────
	$raw    = $_POST['fields'] ?? [];
	$fields = [];

	foreach ( $raw as $key => $field ) {
		$key   = sanitize_key( $key );
		$type  = sanitize_key( $field['type']  ?? 'text' );
		$value = wp_unslash(   $field['value'] ?? '' );

		$fields[ $key ] = [
			'label'    => sanitize_text_field( wp_unslash( $field['label'] ?? $key ) ),
			'type'     => $type,
			'required' => ! empty( $field['required'] ),
			'value'    => match ( $type ) {
				'email'    => sanitize_email( $value ),
				'textarea' => sanitize_textarea_field( $value ),
				'url'      => esc_url_raw( $value ),
				default    => sanitize_text_field( $value ),
			},
		];
	}

	// ── Validate required fields ──────────────────────────────────────────────
	foreach ( $fields as $field ) {
		if ( $field['required'] && ! $field['value'] ) {
			wp_send_json_error( [ 'message' => __( 'Please fill in all required fields.', 'matize' ) ], 422 );
		}
		if ( $field['type'] === 'email' && $field['value'] && ! is_email( $field['value'] ) ) {
			wp_send_json_error( [ 'message' => __( 'Please enter a valid email address.', 'matize' ) ], 422 );
		}
	}

	// ── Build and send email ──────────────────────────────────────────────────
	$form_name  = sanitize_text_field( wp_unslash( $_POST['form_name'] ?? __( 'Form Submission', 'matize' ) ) );
	$cta        = function_exists( 'get_field' ) ? ( get_field( 'mtz_cta_enquiry', 'option' ) ?: [] ) : [];
	$to_email   = $cta['mtz_cta_enquiry_email'] ?? '';
	$to_name    = $cta['mtz_cta_enquiry_name']  ?? '';
	$to         = $to_email
		? ( $to_name ? "{$to_name} <{$to_email}>" : $to_email )
		: get_option( 'admin_email' );
	$subject    = sprintf( '[%s] %s', get_bloginfo( 'name' ), $form_name );

	// Reply-To: first email field, first name-like field
	$reply_to   = '';
	$reply_name = '';
	foreach ( $fields as $field ) {
		if ( ! $reply_to && $field['type'] === 'email' && $field['value'] ) {
			$reply_to = $field['value'];
		}
		if ( ! $reply_name && in_array( $field['type'], [ 'text', 'tel' ], true ) && $field['value'] ) {
			$reply_name = $field['value'];
		}
	}

	$headers = [ 'Content-Type: text/html; charset=UTF-8' ];
	if ( $reply_to ) {
		$safe_name  = str_replace( [ "\r", "\n" ], '', sanitize_text_field( $reply_name ) );
		$safe_email = sanitize_email( $reply_to );
		$headers[]  = "Reply-To: {$safe_name} <{$safe_email}>";
	}

	$sent = wp_mail( $to, $subject, mtz_build_email_body( $form_name, $fields ), $headers );

	if ( ! $sent ) {
		wp_send_json_error( [ 'message' => __( 'Something went wrong. Please try again.', 'matize' ) ], 500 );
	}

	// ── Confirmation email to submitter ───────────────────────────────────────
	$reply_sent = false;
	if ( MTZ_FORM_AUTO_REPLY ) {
		$confirm_subject = sprintf( '%s — %s', get_bloginfo( 'name' ), __( 'We received your enquiry', 'matize' ) );
		$confirm_headers = [
			'Content-Type: text/html; charset=UTF-8',
			'Reply-To: ' . $to,
		];
		$confirm_intro = '<p class="email-intro">' . sprintf(
			__( 'Dear %s, thank you for getting in touch. We have received your message and will be in touch with you shortly. Below you will find a copy of your message for your records.', 'matize' ),
			esc_html( $reply_name )
		) . '</p>';
		$reply_sent = wp_mail( $reply_to, $confirm_subject, mtz_build_email_body( $form_name, $fields, $confirm_intro ), $confirm_headers );
	}

	// ── Success response ──────────────────────────────────────────────────────
	$messages = [ __( 'Thank you for reaching out. We\'ll be in touch soon.', 'matize' ) ];
	if ( $reply_sent ) {
		$messages[] = __( 'A copy of your message has been sent to your email address.', 'matize' );
	}
	wp_send_json_success( [ 'message' => implode( ' ', $messages ) ] );
}

// ─── Contact & social placeholders ──────────────────────────────────────────────

/**
 * Breaks Gmail's phone/email auto-linking (it re-styles any detected number
 * or address as its own default blue link, ignoring the anchor's own inline
 * style) by inserting a zero-width non-joiner between characters — invisible
 * to readers, but enough to stop Gmail's pattern matching from firing.
 */
function mtz_break_gmail_autolink( string $escaped_text ): string {
	return preg_replace( '/([0-9])/', '$1&zwnj;', $escaped_text );
}

/**
 * Builds the %CONTACT_*% placeholder values from the theme's contact info
 * option (ACF options page).
 *
 * Email/phone/address are exposed both as raw pieces — display value
 * (%CONTACT_EMAIL%/%CONTACT_PHONE%/%CONTACT_ADDRESS%) separate from the link
 * target (%CONTACT_EMAIL_URL%/%CONTACT_PHONE_HREF%/%CONTACT_ADDRESS_URL%) —
 * so a template can build its own `<a>` tag with its own inline style (MJML
 * compiles mj-text/attribute styling reliably; raw HTML substituted in after
 * the MJML build has none of that, so it needs the style written by hand in
 * the template source), and pre-assembled as %CONTACT_BLOCK% (already-linked,
 * for templates that just want the whole thing in one place, e.g. the
 * field-agnostic generic one).
 *
 * @return array<string,string> Placeholder token => HTML value.
 */
function mtz_get_contact_placeholders(): array {
	$contact = function_exists( 'get_field' ) ? ( get_field( 'mtz_contact', 'option' ) ?: [] ) : [];

	$contact_address     = ! empty( $contact['mtz_contact_address'] ) ? wp_kses( $contact['mtz_contact_address'], [ 'br' => [] ] ) : '';
	$contact_address_url = ! empty( $contact['mtz_contact_address_url'] )
		? esc_url( $contact['mtz_contact_address_url'] )
		: ( $contact_address ? esc_url( 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode( wp_strip_all_tags( $contact['mtz_contact_address'] ) ) ) : '' );

	$contact_email     = ! empty( $contact['mtz_contact_email'] ) ? mtz_break_gmail_autolink( esc_html( $contact['mtz_contact_email'] ) ) : '';
	$contact_email_url = $contact_email ? 'mailto:' . esc_attr( $contact['mtz_contact_email'] ) : '';

	$contact_phone      = ! empty( $contact['mtz_contact_phone'] ) ? mtz_break_gmail_autolink( esc_html( $contact['mtz_contact_phone'] ) ) : '';
	$contact_phone_href = $contact_phone ? 'tel:' . esc_attr( preg_replace( '/\s+/', '', $contact['mtz_contact_phone'] ) ) : '';

	$contact_parts = array_filter( [
		$contact_address_url ? '<a href="' . $contact_address_url . '">' . $contact_address . '</a>' : $contact_address,
		$contact_email ? '<a href="' . esc_url( $contact_email_url ) . '">' . $contact_email . '</a>' : '',
		$contact_phone ? '<a href="' . esc_url( $contact_phone_href ) . '">' . $contact_phone . '</a>' : '',
	] );

	return [
		'%CONTACT_ADDRESS%'     => $contact_address,
		'%CONTACT_ADDRESS_URL%' => $contact_address_url,
		'%CONTACT_EMAIL%'       => $contact_email,
		'%CONTACT_EMAIL_URL%'   => $contact_email_url,
		'%CONTACT_PHONE%'       => $contact_phone,
		'%CONTACT_PHONE_HREF%'  => $contact_phone_href,
		'%CONTACT_BLOCK%'       => $contact_parts ? '<p>' . implode( '<br>', $contact_parts ) . '</p>' : '',
	];
}

/**
 * Builds the %SOCIAL_<PLATFORM>_URL% placeholder values from the theme's
 * social links option (ACF options page) — raw URL only (empty string if not
 * set). No markup, no styling: which platforms to show, in what order, and
 * how to style them is entirely up to the template.
 *
 * @return array<string,string> Placeholder token => URL.
 */
function mtz_get_social_placeholders(): array {
	$social = function_exists( 'get_field' ) ? ( get_field( 'mtz_social', 'option' ) ?: [] ) : [];

	return [
		'%SOCIAL_INSTAGRAM_URL%' => ! empty( $social['mtz_social_instagram'] ) ? esc_url( $social['mtz_social_instagram'] ) : '',
		'%SOCIAL_FACEBOOK_URL%'  => ! empty( $social['mtz_social_facebook'] )  ? esc_url( $social['mtz_social_facebook'] )  : '',
		'%SOCIAL_PINTEREST_URL%' => ! empty( $social['mtz_social_pinterest'] ) ? esc_url( $social['mtz_social_pinterest'] ) : '',
		'%SOCIAL_LINKEDIN_URL%'  => ! empty( $social['mtz_social_linkedin'] )  ? esc_url( $social['mtz_social_linkedin'] )  : '',
	];
}

// ─── Email body ───────────────────────────────────────────────────────────────

/**
 * Builds the HTML body for a form-submission email.
 *
 * Templates are resolved in this order: the explicit $template argument, if
 * given; otherwise the 'mtz_email_template' filter, which a theme can hook
 * into to supply its own bespoke template for a given $form_name (e.g. named
 * field placeholders, on-brand styling) without this plugin ever needing to
 * know the theme's file structure; otherwise this plugin's own generic
 * template, which only relies on %FIELDS% (built dynamically below from
 * whatever fields were actually submitted) so it works for any form.
 *
 * Every submitted field is also exposed as its own %<field_key>% placeholder
 * (e.g. %mtz_email%) alongside the aggregate %FIELDS% table, so a bespoke
 * template can lay fields out individually instead of using the generic table.
 *
 * @param string      $form_name
 * @param array       $fields
 * @param string      $intro
 * @param string|null $template  Absolute path to a template file. Bypasses
 *                                the 'mtz_email_template' filter entirely.
 * @return string
 */
function mtz_build_email_body( string $form_name, array $fields, string $intro = '', ?string $template = null ): string {
	$default_template = plugin_dir_path( dirname( __DIR__ ) ) . 'templates/generic.html';
	$is_reply          = $intro !== '';
	$template          = $template ?? apply_filters( 'mtz_email_template', $default_template, $form_name, $is_reply );

	if ( ! file_exists( $template ) ) {
		error_log( 'Matize: email template not found at ' . $template );
		$lines = [ "<h2>{$form_name}</h2>" ];
		if ( $intro ) $lines[] = wp_strip_all_tags( $intro );
		foreach ( $fields as $field ) {
			if ( ! $field['value'] ) continue;
			$lines[] = esc_html( $field['label'] ) . ': ' . esc_html( $field['value'] );
		}
		return implode( "\n", $lines );
	}

	// $field_placeholders lets a bespoke template reference a submitted field
	// individually (e.g. %mtz_email%) instead of the generic %FIELDS% table.
	$fields_html       = '';
	$field_placeholders = [];
	foreach ( $fields as $key => $field ) {
		$field_placeholders[ '%' . $key . '%' ] = nl2br( esc_html( $field['value'] ) );

		if ( ! $field['value'] ) continue;
		$label        = esc_html( $field['label'] );
		$value        = nl2br( esc_html( $field['value'] ) );
		$fields_html .= "
			<tr>
				<td class=\"label-cell\">{$label}</td>
				<td class=\"value-cell\">{$value}</td>
			</tr>";
	}

	$html     = file_get_contents( $template );
	$logo_url = plugin_dir_url( dirname( __DIR__ ) ) . 'templates/mtz-logo-600x190.png';

	$contact_placeholders = mtz_get_contact_placeholders();
	$social_placeholders  = mtz_get_social_placeholders();

	$html = str_replace(
		array_merge(
			[ '%SITE_NAME%', '%SITE_URL%', '%LOGO_URL%', '%FORM_NAME%', '%FIELDS%', '%INTRO%', '%YEAR%' ],
			array_keys( $contact_placeholders ),
			array_keys( $social_placeholders ),
			array_keys( $field_placeholders )
		),
		array_merge(
			[ esc_html( get_bloginfo( 'name' ) ), esc_url( home_url( '/' ) ), esc_url( $logo_url ), esc_html( $form_name ), $fields_html, $intro, gmdate( 'Y' ) ],
			array_values( $contact_placeholders ),
			array_values( $social_placeholders ),
			array_values( $field_placeholders )
		),
		$html
	);

	return $html;
}
