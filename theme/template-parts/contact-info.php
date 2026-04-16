<?php
/**
 * Contact info component — wraps contact-details, address, and social-links.
 * Used in: footer.php, page-contact.php
 */

$contact = mtz_option( 'mtz_contact' );
$address = $contact['mtz_contact_address'] ?? '';
?>

<div class="contact-info">
	<?php get_template_part( 'template-parts/contact-details' ); ?>

	<?php if ( $address ) : ?>
		<p class="contact-address">
			<i data-lucide="map-pin" aria-hidden="true"></i>
			<span><?php echo wp_kses( $address, [ 'br' => [] ] ); ?></span>
		</p>
	<?php endif; ?>

	<?php get_template_part( 'template-parts/social-links' ); ?>
</div>
