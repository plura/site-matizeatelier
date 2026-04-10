<?php
/**
 * Contact form component.
 * Used in: template-parts/cta.php (modal), page-contact.php
 * Submission handled via AJAX — see plugin/includes/core/form.php
 */
?>

<form
	class="contact-form"
	data-mtz-form
	data-form-name="<?php esc_attr_e( 'New Enquiry', 'matize' ); ?>"
	novalidate
>
	<!-- Honeypot — hidden from real users, bots fill it -->
	<input type="text" name="mtz_website" tabindex="-1" autocomplete="off" aria-hidden="true" style="display:none">

	<label class="contact-form__field">
		<?php esc_html_e( 'Name', 'matize' ); ?>
		<input type="text" name="mtz_name" required placeholder="<?php esc_attr_e( 'Your name', 'matize' ); ?>">
	</label>

	<label class="contact-form__field">
		<?php esc_html_e( 'Email', 'matize' ); ?>
		<input type="email" name="mtz_email" required placeholder="<?php esc_attr_e( 'Your email', 'matize' ); ?>">
	</label>

	<label class="contact-form__field">
		<?php esc_html_e( 'Phone', 'matize' ); ?>
		<input type="tel" name="mtz_phone" placeholder="<?php esc_attr_e( 'Your phone number', 'matize' ); ?>">
	</label>

	<label class="contact-form__field">
		<?php esc_html_e( 'Message', 'matize' ); ?>
		<textarea name="mtz_message" required placeholder="<?php esc_attr_e( 'Your message', 'matize' ); ?>" rows="5"></textarea>
	</label>

	<div class="contact-form__footer">
		<p class="contact-form__feedback" aria-live="polite"></p>
		<button type="submit" class="contact-form__submit">
			<?php esc_html_e( 'Send', 'matize' ); ?>
		</button>
	</div>

</form>
