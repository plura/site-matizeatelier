<?php
/**
 * Contact details component — email, phone (address is rendered by contact-info.php).
 * Data from ACF options via mtz_option().
 */

$contact = mtz_option( 'mtz_contact' );
$email   = $contact['mtz_contact_email'] ?? '';
$phone   = $contact['mtz_contact_phone'] ?? '';

if ( ! $email && ! $phone ) return;
?>

<address class="contact-details">

	<?php if ( $email ) : ?>
		<a href="mailto:<?php echo esc_attr( $email ); ?>" class="contact-details__item">
			<?php echo mtz_icon( 'mail' ); ?>
			<?php echo esc_html( $email ); ?>
		</a>
	<?php endif; ?>

	<?php if ( $phone ) : ?>
		<a href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', $phone ) ); ?>" class="contact-details__item">
			<?php echo mtz_icon( 'phone' ); ?>
			<?php echo esc_html( $phone ); ?>
		</a>
	<?php endif; ?>

</address>
