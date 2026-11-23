<?php
/**
 * Contact form component.
 * Used in: template-parts/cta.php (modal), page-contact.php
 * Submission handled via AJAX — see plugin/includes/core/form.php
 */

$lang        = matize_brevo_lang( apply_filters( 'wpml_current_language', null ) );
$privacy_url = matize_brevo_page_url( 'politica-de-privacidade', $lang );
?>

<form
	class="contact-form mtz-form"
	data-mtz-form
	data-form-name="<?php esc_attr_e( 'New Enquiry', 'matize' ); ?>"
	data-lang="<?php echo esc_attr( $lang ); ?>"
	novalidate
>
	<!-- Honeypot — hidden from real users, bots fill it -->
	<input type="text" name="mtz_website" tabindex="-1" autocomplete="off" aria-hidden="true" style="display:none">

	<label class="mtz-form__field">
		<?php esc_html_e( 'Name', 'matize' ); ?>
		<input type="text" name="mtz_name" required placeholder="<?php esc_attr_e( 'Your name', 'matize' ); ?>">
	</label>

	<label class="mtz-form__field">
		<?php esc_html_e( 'Email', 'matize' ); ?>
		<input type="email" name="mtz_email" required placeholder="<?php esc_attr_e( 'Your email', 'matize' ); ?>">
	</label>

	<label class="mtz-form__field">
		<?php esc_html_e( 'Phone', 'matize' ); ?>
		<input type="tel" name="mtz_phone" placeholder="<?php esc_attr_e( 'Your phone number', 'matize' ); ?>">
	</label>

	<label class="mtz-form__field">
		<?php esc_html_e( 'Message', 'matize' ); ?>
		<textarea name="mtz_message" required placeholder="<?php esc_attr_e( 'Your message', 'matize' ); ?>" rows="5"></textarea>
	</label>

	<?php if ( matize_brevo_is_configured() ) : ?>
		<!-- Link sits outside the label so opening it doesn't toggle the checkbox. -->
		<div class="mtz-form__optin">
			<label>
				<input type="checkbox" name="newsletter_optin" value="1">
				<span><?php esc_html_e( "I'd like to receive news from Matize Atelier by email.", 'matize' ); ?></span>
			</label>
			<?php if ( $privacy_url ) : ?>
				<a href="<?php echo esc_url( $privacy_url ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Privacy policy', 'matize' ); ?></a>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<div class="mtz-form__footer">
		<p class="mtz-form__feedback" aria-live="polite"></p>
		<button type="submit" class="mtz-form__submit">
			<?php esc_html_e( 'Send', 'matize' ); ?>
		</button>
	</div>

</form>
