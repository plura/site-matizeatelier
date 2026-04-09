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
>
	<!-- Honeypot — hidden from real users, bots fill it -->
	<input type="text" name="mtz_website" tabindex="-1" autocomplete="off" aria-hidden="true" style="display:none">

	<label class="contact-form__field">
		<?php esc_html_e( 'Nome', 'matize' ); ?>
		<input type="text" name="mtz_name" required placeholder="<?php esc_attr_e( 'O seu nome', 'matize' ); ?>">
	</label>

	<label class="contact-form__field">
		<?php esc_html_e( 'Email', 'matize' ); ?>
		<input type="email" name="mtz_email" required placeholder="<?php esc_attr_e( 'O seu email', 'matize' ); ?>">
	</label>

	<label class="contact-form__field">
		<?php esc_html_e( 'Telefone', 'matize' ); ?>
		<input type="tel" name="mtz_phone" placeholder="<?php esc_attr_e( 'O seu telefone', 'matize' ); ?>">
	</label>

	<label class="contact-form__field">
		<?php esc_html_e( 'Mensagem', 'matize' ); ?>
		<textarea name="mtz_message" required placeholder="<?php esc_attr_e( 'A sua mensagem', 'matize' ); ?>" rows="5"></textarea>
	</label>

	<div class="contact-form__footer">
		<p class="contact-form__feedback" aria-live="polite"></p>
		<button type="submit" class="contact-form__submit">
			<?php esc_html_e( 'Enviar', 'matize' ); ?>
		</button>
	</div>

</form>
