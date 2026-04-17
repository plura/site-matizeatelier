<?php
if ( is_page_template( 'page-contact.php' ) ) return;

$cta_enquiry = get_field( 'mtz_cta_enquiry', 'option' ) ?: [];
$cta_label   = $cta_enquiry['label'] ?? '';
?>

<button class="btn btn--primary cta__btn" aria-haspopup="dialog" aria-controls="contact-modal">
	<?php echo esc_html( $cta_label ?: __( 'Get in Touch', 'matize' ) ); ?>
	<i data-lucide="send" aria-hidden="true"></i>
</button>

<?php if ( ! defined( 'MTZ_CONTACT_MODAL_RENDERED' ) ) :
	define( 'MTZ_CONTACT_MODAL_RENDERED', true ); ?>

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

<?php endif; ?>
