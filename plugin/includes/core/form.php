<?php
/**
 * Generic AJAX form handler.
 *
 * Handles form submissions via fetch(). Sanitizes fields by input type.
 * Each form passes its fields as a structured array — no hardcoded field names.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// ─── Localize nonce + ajaxUrl to JS ──────────────────────────────────────────

add_action( 'wp_enqueue_scripts', function () {
	wp_localize_script( 'matize-main', 'mtzForms', [
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
	$site_name = get_bloginfo( 'name' );
	$site_url  = home_url( '/' );
	$rows      = '';

	foreach ( $fields as $field ) {
		if ( ! $field['value'] ) continue;
		$label = esc_html( $field['label'] );
		$value = nl2br( esc_html( $field['value'] ) );
		$rows .= "
			<tr>
				<td style='padding:8px 24px;color:#9E948A;font-size:11px;text-transform:uppercase;letter-spacing:0.1em;white-space:nowrap;vertical-align:top'>{$label}</td>
				<td style='padding:8px 24px;color:#2A2420;font-size:14px;vertical-align:top'>{$value}</td>
			</tr>";
	}

	return "<!DOCTYPE html>
<html lang='en'>
<head><meta charset='UTF-8'></head>
<body style='margin:0;padding:0;background:#F5F0EA;font-family:Georgia,serif'>
	<table width='100%' cellpadding='0' cellspacing='0' style='background:#F5F0EA;padding:40px 0'>
		<tr><td align='center'>
			<table width='560' cellpadding='0' cellspacing='0' style='background:#ffffff;max-width:560px;width:100%'>

				<tr>
					<td colspan='2' style='background:#2A2420;padding:32px 40px'>
						<a href='{$site_url}' style='color:#F5F0EA;font-size:18px;font-weight:300;letter-spacing:0.2em;text-decoration:none;text-transform:uppercase'>{$site_name}</a>
					</td>
				</tr>

				<tr>
					<td colspan='2' style='padding:32px 24px 8px;color:#9E948A;font-size:11px;letter-spacing:0.15em;text-transform:uppercase'>{$form_name}</td>
				</tr>

				{$rows}

				<tr>
					<td colspan='2' style='padding:32px 24px;border-top:1px solid #F5F0EA;color:#9E948A;font-size:11px;letter-spacing:0.08em'>
						{$site_name} &mdash; <a href='{$site_url}' style='color:#9E948A'>{$site_url}</a>
					</td>
				</tr>

			</table>
		</td></tr>
	</table>
</body>
</html>";
}
