<?php
/**
 * Generic AJAX form handler.
 *
 * Handles form submissions via fetch(). Sanitizes fields by input type.
 * Each form passes its fields as a structured array — no hardcoded field names.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// ─── Enqueue + localize ───────────────────────────────────────────────────────

add_action( 'wp_enqueue_scripts', function () {
	$file = plugin_dir_path( dirname( __DIR__ ) ) . 'assets/js/form.js';
	$url  = plugin_dir_url( dirname( __DIR__ ) ) . 'assets/js/form.js';

	wp_enqueue_script( 'matize-form', $url, [], filemtime( $file ), true );

	wp_localize_script( 'matize-form', 'mtzForms', [
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
	$to         = get_option( 'admin_email' );
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
		$headers[] = "Reply-To: {$reply_name} <{$reply_to}>";
	}

	$sent = wp_mail( $to, $subject, mtz_build_email_body( $form_name, $fields ), $headers );

	if ( $sent ) {
		wp_send_json_success( [ 'message' => __( 'Thank you. We will be in touch shortly.', 'matize' ) ] );
	} else {
		wp_send_json_error( [ 'message' => __( 'Something went wrong. Please try again.', 'matize' ) ], 500 );
	}
}

// ─── Email body ───────────────────────────────────────────────────────────────

function mtz_build_email_body( string $form_name, array $fields ): string {
	$template = plugin_dir_path( dirname( __DIR__ ) ) . 'templates/email-enquiry.html';

	if ( ! file_exists( $template ) ) {
		return '';
	}

	$fields_html = '';
	foreach ( $fields as $field ) {
		if ( ! $field['value'] ) continue;
		$label        = esc_html( $field['label'] );
		$value        = nl2br( esc_html( $field['value'] ) );
		$fields_html .= "
			<div class=\"field\">
				<span class=\"field__label\">{$label}</span>
				<span class=\"field__value\">{$value}</span>
			</div>";
	}

	$html = file_get_contents( $template );
	$html = str_replace(
		[ '%SITE_NAME%', '%SITE_URL%', '%FORM_NAME%', '%FIELDS%' ],
		[ esc_html( get_bloginfo( 'name' ) ), esc_url( home_url( '/' ) ), esc_html( $form_name ), $fields_html ],
		$html
	);

	return $html;
}
