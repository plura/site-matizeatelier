<?php
/**
 * Contact form component.
 * Used in: template-parts/cta.php (modal), page-contact.php
 */

$status = sanitize_key( $_GET['contact'] ?? '' );
?>

<?php if ( $status === 'success' ) : ?>
	<p class="contact-form__feedback contact-form__feedback--success">
		<?php esc_html_e( 'Thank you. We will be in touch shortly.', 'matize' ); ?>
	</p>
<?php elseif ( $status === 'error' ) : ?>
	<p class="contact-form__feedback contact-form__feedback--error">
		<?php esc_html_e( 'Something went wrong. Please try again.', 'matize' ); ?>
	</p>
<?php endif; ?>

<form class="contact-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" novalidate>

	<input type="hidden" name="action" value="mtz_contact">
	<?php wp_nonce_field( 'mtz_contact', 'mtz_contact_nonce' ); ?>

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

	<button type="submit" class="contact-form__submit">
		<?php esc_html_e( 'Enviar', 'matize' ); ?>
	</button>

</form>
