<?php
$cta_enquiry = get_field( 'mtz_cta_enquiry', 'option' ) ?: [];
$cta_label   = $cta_enquiry['label'] ?? '';
?>

<button class="cta__btn" popovertarget="contact-modal">
	<?php echo esc_html( $cta_label ?: __( 'Get in Touch', 'matize' ) ); ?>
</button>

<?php if ( ! defined( 'MTZ_CONTACT_MODAL_RENDERED' ) ) :
	define( 'MTZ_CONTACT_MODAL_RENDERED', true ); ?>
	<div id="contact-modal" popover>
		<button
			class="contact-modal__close"
			popovertarget="contact-modal"
			popovertargetaction="hide"
			aria-label="<?php esc_attr_e( 'Close', 'matize' ); ?>"
		>&times;</button>
		<?php echo do_shortcode( '[contact-form-7 id="contact" title="Contacto"]' ); ?>
	</div>
<?php endif; ?>
