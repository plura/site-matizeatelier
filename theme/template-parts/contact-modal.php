<?php
/**
 * Contact modal — rendered once before </body> via footer.php.
 * Opened by .cta__btn via showModal().
 */
if ( defined( 'MTZ_CONTACT_MODAL_RENDERED' ) ) return;
define( 'MTZ_CONTACT_MODAL_RENDERED', true );
?>

<dialog id="contact-modal" class="contact-modal">
	<div class="contact-modal__inner">
		<button
			class="contact-modal__close"
			aria-label="<?php esc_attr_e( 'Close', 'matize' ); ?>"
		>
			<i data-lucide="x" aria-hidden="true"></i>
		</button>
		<?php get_template_part( 'template-parts/contact-form' ); ?>
	</div>
</dialog>
