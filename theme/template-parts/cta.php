<?php
if ( is_page_template( 'page-contact.php' ) ) return;

$cta_enquiry = get_field( 'mtz_cta_enquiry', 'option' ) ?: [];
$cta_label   = $cta_enquiry['mtz_cta_enquiry_label'] ?? '';
?>

<button class="btn btn--primary cta__btn" aria-haspopup="dialog" aria-controls="contact-modal">
	<?php echo esc_html( $cta_label ?: __( 'Get in Touch', 'matize' ) ); ?>
	<?php echo mtz_icon( 'send' ); ?>
</button>

<?php if ( ! defined( 'MTZ_CONTACT_MODAL_RENDERED' ) ) :
	define( 'MTZ_CONTACT_MODAL_RENDERED', true ); ?>

	<dialog id="contact-modal" class="contact-modal" aria-modal="true" aria-labelledby="contact-modal-title">
		<div class="contact-modal__inner">
			<h2 id="contact-modal-title" class="contact-modal__title"><?php esc_html_e( 'Contact', 'matize' ); ?></h2>
			<button
				class="contact-modal__close"
				aria-label="<?php esc_attr_e( 'Close', 'matize' ); ?>"
			>
				<?php echo mtz_icon( 'x' ); ?>
			</button>
			<?php get_template_part( 'template-parts/contact-form' ); ?>
		</div>
	</dialog>

<?php endif; ?>
