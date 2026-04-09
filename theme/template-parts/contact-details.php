<?php
/**
 * Contact details component — email, phone, address.
 * Data from ACF options via mtz_option().
 */

$contact = mtz_option( 'mtz_contact' );
$email   = $contact['mtz_contact_email']   ?? '';
$phone   = $contact['mtz_contact_phone']   ?? '';
$address = $contact['mtz_contact_address'] ?? '';

if ( ! $email && ! $phone && ! $address ) return;
?>

<address class="contact-details">

	<?php if ( $email ) : ?>
		<a href="mailto:<?php echo esc_attr( $email ); ?>" class="contact-details__item">
			<i data-lucide="mail" aria-hidden="true"></i>
			<?php echo esc_html( $email ); ?>
		</a>
	<?php endif; ?>

	<?php if ( $phone ) : ?>
		<a href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', $phone ) ); ?>" class="contact-details__item">
			<i data-lucide="phone" aria-hidden="true"></i>
			<?php echo esc_html( $phone ); ?>
		</a>
	<?php endif; ?>

	<?php if ( $address ) : ?>
		<p class="contact-details__item contact-details__address">
			<i data-lucide="map-pin" aria-hidden="true"></i>
			<span><?php echo wp_kses( $address, [ 'br' => [] ] ); ?></span>
		</p>
	<?php endif; ?>

</address>
