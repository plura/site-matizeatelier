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
		<?php
		$maps_url = 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode( wp_strip_all_tags( $address ) );
		echo plura_wp_link(
			'<i data-lucide="map-pin" aria-hidden="true"></i><span>' . wp_kses( $address, [ 'br' => [] ] ) . '</span>',
			$maps_url,
			[ 'class' => 'contact-address', 'target' => '_blank' ],
			true
		);
		?>
	<?php endif; ?>

	<?php get_template_part( 'template-parts/social-links' ); ?>
</div>
