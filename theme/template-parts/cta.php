<?php
$cta_enquiry = get_field( 'mtz_cta_enquiry', 'option' ) ?: [];
$cta_label   = $cta_enquiry['label'] ?? '';
?>

<button class="cta__btn" aria-haspopup="dialog" aria-controls="contact-modal">
	<?php echo esc_html( $cta_label ?: __( 'Get in Touch', 'matize' ) ); ?>
</button>

<?php get_template_part( 'template-parts/contact-modal' ); ?>
