<?php
/**
 * Optional newsletter signup on the contact form, via Brevo double opt-in.
 * The API key is set in Theme Settings: while it's empty, the checkbox isn't
 * rendered and nothing is sent.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

const MTZ_BREVO_LIST_ID       = 3;
const MTZ_BREVO_DOI_TEMPLATES = [ 'pt' => 1, 'en' => 2 ];

/**
 * Reduces a WPML code (Portuguese is "pt-pt") or posted value to pt or en.
 *
 * @param string|null $lang Language code.
 * @return string "en", or "pt" (the site default) for anything else.
 */
function matize_brevo_lang( ?string $lang ): string {
	return str_starts_with( strtolower( (string) $lang ), 'en' ) ? 'en' : 'pt';
}

/**
 * Reads the API key from Theme Settings. Uses the raw options because ACF can
 * store options per WPML language, and get_field() on /en/ would miss a key
 * saved while the admin was in PT.
 *
 * @return string The key, or an empty string if unset.
 */
function matize_brevo_api_key(): string {
	return trim( (string) ( get_option( 'options_mtz_brevo_api_key' ) ?: get_option( 'options_en_mtz_brevo_api_key' ) ) );
}

/**
 * Whether the Brevo API key has been set.
 *
 * @return bool
 */
function matize_brevo_is_configured(): bool {
	return '' !== matize_brevo_api_key();
}

/**
 * Resolves a page's URL in the given language from its default-language slug.
 * get_posts() suppresses filters by default, so WPML can't hide the PT page
 * during an EN request.
 *
 * @param string $slug Slug of the Portuguese page.
 * @param string $lang "pt" or "en".
 * @return string Permalink, or an empty string if the page or translation is missing.
 */
function matize_brevo_page_url( string $slug, string $lang ): string {
	$ids = get_posts( [
		'name'        => $slug,
		'post_type'   => 'page',
		'post_status' => 'publish',
		'numberposts' => 1,
		'fields'      => 'ids',
	] );
	if ( ! $ids ) return '';

	$id = apply_filters( 'wpml_object_id', $ids[0], 'page', false, 'en' === $lang ? 'en' : 'pt-pt' );
	return $id ? (string) get_permalink( $id ) : '';
}

/**
 * Requests a double opt-in subscription. Failures only reach error_log.
 *
 * @param string $email Validated contact email.
 * @param string $name  Full name; only the first word is sent.
 * @param string $lang  Language code.
 * @return bool True if Brevo accepted the request.
 */
function matize_brevo_subscribe( string $email, string $name, string $lang ): bool {
	if ( ! matize_brevo_is_configured() || ! is_email( $email ) ) return false;

	$lang     = matize_brevo_lang( $lang );
	$redirect = matize_brevo_page_url( 'newsletter-confirmada', $lang )
		?: apply_filters( 'wpml_permalink', home_url( '/' ), 'en' === $lang ? 'en' : 'pt-pt' );

	$response = wp_remote_post( 'https://api.brevo.com/v3/contacts/doubleOptinConfirmation', [
		'timeout' => 3,
		'headers' => [
			'accept'       => 'application/json',
			'api-key'      => matize_brevo_api_key(),
			'content-type' => 'application/json',
		],
		'body'    => wp_json_encode( [
			'email'          => $email,
			'attributes'     => array_filter( [ 'FIRSTNAME' => strtok( trim( $name ), ' ' ), 'LANGUAGE' => $lang ] ),
			'includeListIds' => [ MTZ_BREVO_LIST_ID ],
			'templateId'     => MTZ_BREVO_DOI_TEMPLATES[ $lang ],
			'redirectionUrl' => $redirect,
		] ),
	] );

	if ( is_wp_error( $response ) ) {
		error_log( 'Matize Brevo: ' . $response->get_error_message() );
		return false;
	}

	$status = (int) wp_remote_retrieve_response_code( $response );
	if ( $status >= 200 && $status < 300 ) return true;

	// Brevo's message can quote the address, which shouldn't end up in the log.
	$error = json_decode( wp_remote_retrieve_body( $response ), true );
	error_log( sprintf(
		'Matize Brevo: HTTP %d %s %s',
		$status,
		is_array( $error ) ? (string) ( $error['code'] ?? '' ) : '',
		is_array( $error ) ? str_replace( $email, '[email]', (string) ( $error['message'] ?? '' ) ) : ''
	) );
	return false;
}

// Runs only after the enquiry was sent; honeypot and rate-limited requests exit earlier.
add_filter( 'mtz_form_success_messages', function ( array $messages, array $fields ): array {
	if ( '1' !== ( $_POST['newsletter_optin'] ?? '' ) ) return $messages;

	try {
		if ( matize_brevo_subscribe( $fields['mtz_email']['value'] ?? '', $fields['mtz_name']['value'] ?? '', mtz_form_language() ) ) {
			$messages[] = __( 'You will receive an email to confirm your subscription.', 'matize' );
		}
	} catch ( \Throwable $e ) {
		error_log( 'Matize Brevo: ' . $e->getMessage() );
	}

	return $messages;
}, 10, 2 );
