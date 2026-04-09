<?php
/**
 * Contact form handler.
 *
 * Processes POST submissions from template-parts/contact-form.php.
 * Uses wp_mail() for delivery, nonce + honeypot for security.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'admin_post_nopriv_mtz_contact', 'mtz_handle_contact' );
add_action( 'admin_post_mtz_contact',        'mtz_handle_contact' );

function mtz_handle_contact(): void {

	// ── Nonce ────────────────────────────────────────────────────────────────
	if ( ! isset( $_POST['mtz_contact_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['mtz_contact_nonce'] ) ), 'mtz_contact' ) ) {
		mtz_contact_redirect( 'error' );
	}

	// ── Honeypot ─────────────────────────────────────────────────────────────
	if ( ! empty( $_POST['mtz_website'] ) ) {
		// Silent discard — bot filled the hidden field
		mtz_contact_redirect( 'success' );
	}

	// ── Sanitize fields ───────────────────────────────────────────────────────
	$name    = sanitize_text_field( wp_unslash( $_POST['mtz_name']    ?? '' ) );
	$email   = sanitize_email(      wp_unslash( $_POST['mtz_email']   ?? '' ) );
	$phone   = sanitize_text_field( wp_unslash( $_POST['mtz_phone']   ?? '' ) );
	$message = sanitize_textarea_field( wp_unslash( $_POST['mtz_message'] ?? '' ) );

	// ── Validate required fields ──────────────────────────────────────────────
	if ( ! $name || ! is_email( $email ) || ! $message ) {
		mtz_contact_redirect( 'error' );
	}

	// ── Build email ───────────────────────────────────────────────────────────
	$to      = get_option( 'admin_email' );
	$subject = sprintf( '[%s] %s', get_bloginfo( 'name' ), __( 'New Enquiry', 'matize' ) );

	$body  = "Name: {$name}\n";
	$body .= "Email: {$email}\n";
	if ( $phone ) {
		$body .= "Phone: {$phone}\n";
	}
	$body .= "\nMessage:\n{$message}\n";

	$headers = [
		'Content-Type: text/plain; charset=UTF-8',
		"Reply-To: {$name} <{$email}>",
	];

	$sent = wp_mail( $to, $subject, $body, $headers );

	mtz_contact_redirect( $sent ? 'success' : 'error' );
}

/**
 * Redirect back to the referring page with a status query arg.
 *
 * @param string $status 'success' | 'error'
 */
function mtz_contact_redirect( string $status ): never {
	$referer = wp_get_referer() ?: home_url( '/' );
	wp_safe_redirect( add_query_arg( 'contact', $status, $referer ) );
	exit;
}
