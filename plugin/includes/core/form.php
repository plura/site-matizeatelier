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

// ─── Email body ───────────────────────────────────────────────────────────────

function mtz_build_email_body( string $form_name, array $fields, string $intro = '' ): string {
	$template = plugin_dir_path( dirname( __DIR__ ) ) . 'templates/email-enquiry.html';

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

	$fields_html = '';
	foreach ( $fields as $field ) {
		if ( ! $field['value'] ) continue;
		$label        = esc_html( $field['label'] );
		$value        = nl2br( esc_html( $field['value'] ) );
		$fields_html .= "
			<tr>
				<td class=\"label-cell\">{$label}</td>
				<td class=\"value-cell\">{$value}</td>
			</tr>";
	}

	$html = file_get_contents( $template );
	$logo_url = plugin_dir_url( dirname( __DIR__ ) ) . 'templates/mtz-logo-600x190.png';

	// ── Contact block ─────────────────────────────────────────────────────────
	$contact       = function_exists( 'get_field' ) ? ( get_field( 'mtz_contact', 'option' ) ?: [] ) : [];
	$contact_parts = [];
	if ( ! empty( $contact['mtz_contact_address'] ) ) {
		$contact_parts[] = wp_kses( $contact['mtz_contact_address'], [ 'br' => [] ] );
	}
	if ( ! empty( $contact['mtz_contact_email'] ) ) {
		$email           = esc_attr( $contact['mtz_contact_email'] );
		$contact_parts[] = '<a href="mailto:' . $email . '">' . esc_html( $contact['mtz_contact_email'] ) . '</a>';
	}
	if ( ! empty( $contact['mtz_contact_phone'] ) ) {
		$phone           = esc_attr( preg_replace( '/\s+/', '', $contact['mtz_contact_phone'] ) );
		$contact_parts[] = '<a href="tel:' . $phone . '">' . esc_html( $contact['mtz_contact_phone'] ) . '</a>';
	}
	$contact_block = $contact_parts
		? '<p>' . implode( '<br>', $contact_parts ) . '</p>'
		: '';

	// ── Social block ───────────────────────────────────────────────────────────
	$social       = function_exists( 'get_field' ) ? ( get_field( 'mtz_social', 'option' ) ?: [] ) : [];
	$social_links = [
		'Instagram' => $social['mtz_social_instagram'] ?? '',
		'Facebook'  => $social['mtz_social_facebook']  ?? '',
		'Pinterest' => $social['mtz_social_pinterest'] ?? '',
		'LinkedIn'  => $social['mtz_social_linkedin']  ?? '',
	];
	$social_parts = [];
	foreach ( $social_links as $label => $url ) {
		if ( $url ) {
			$social_parts[] = '<a href="' . esc_url( $url ) . '" target="_blank">' . esc_html( $label ) . '</a>';
		}
	}
	$social_block = $social_parts
		? '<p class="footer-social">' . implode( ' &nbsp;&middot;&nbsp; ', $social_parts ) . '</p>'
		: '';

	$html = str_replace(
		[ '%SITE_NAME%', '%SITE_URL%', '%LOGO_URL%', '%FORM_NAME%', '%FIELDS%', '%INTRO%', '%CONTACT_BLOCK%', '%SOCIAL_BLOCK%', '%YEAR%' ],
		[ esc_html( get_bloginfo( 'name' ) ), esc_url( home_url( '/' ) ), esc_url( $logo_url ), esc_html( $form_name ), $fields_html, $intro, $contact_block, $social_block, gmdate( 'Y' ) ],
		$html
	);

	return $html;
}
