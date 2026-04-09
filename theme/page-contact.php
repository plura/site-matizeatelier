<?php
/**
 * Template Name: Contact
 */

$contact_group   = get_field( 'mtz_contact', 'option' ) ?: [];
$contact_email   = $contact_group['mtz_contact_email']   ?? '';
$contact_phone   = $contact_group['mtz_contact_phone']   ?? '';
$contact_address = $contact_group['mtz_contact_address'] ?? '';

$social_group = get_field( 'mtz_social', 'option' ) ?: [];
$social = [
	'Instagram' => [ 'url' => $social_group['mtz_social_instagram'] ?? '', 'icon' => 'instagram' ],
	'Facebook'  => [ 'url' => $social_group['mtz_social_facebook']  ?? '', 'icon' => 'facebook'  ],
	'Pinterest' => [ 'url' => $social_group['mtz_social_pinterest'] ?? '', 'icon' => null        ],
	'LinkedIn'  => [ 'url' => $social_group['mtz_social_linkedin']  ?? '', 'icon' => 'linkedin'  ],
];
$social_filtered = array_filter( $social, fn( $s ) => ! empty( $s['url'] ) );

get_header();
get_template_part( 'template-parts/page-header' ); ?>

<main id="main" class="site-main page-contact">

	<section class="contact">
		<div class="contact__inner container">

			<!-- Info column -->
			<div class="contact__info">

				<?php if ( $contact_email || $contact_phone || $contact_address ) : ?>
					<address class="contact__details">

						<?php if ( $contact_email ) : ?>
							<a href="mailto:<?php echo esc_attr( $contact_email ); ?>" class="contact__detail">
								<i data-lucide="mail" aria-hidden="true"></i>
								<?php echo esc_html( $contact_email ); ?>
							</a>
						<?php endif; ?>

						<?php if ( $contact_phone ) : ?>
							<a href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', $contact_phone ) ); ?>" class="contact__detail">
								<i data-lucide="phone" aria-hidden="true"></i>
								<?php echo esc_html( $contact_phone ); ?>
							</a>
						<?php endif; ?>

						<?php if ( $contact_address ) : ?>
							<p class="contact__detail contact__address">
								<i data-lucide="map-pin" aria-hidden="true"></i>
								<span><?php echo wp_kses( $contact_address, [ 'br' => [] ] ); ?></span>
							</p>
						<?php endif; ?>

					</address>
				<?php endif; ?>

				<?php if ( $social_filtered ) : ?>
					<nav class="contact__social" aria-label="<?php esc_attr_e( 'Redes sociais', 'matize' ); ?>">
						<?php foreach ( $social_filtered as $label => $s ) : ?>
							<a
								href="<?php echo esc_url( $s['url'] ); ?>"
								class="contact__social-link"
								target="_blank"
								rel="noopener noreferrer"
								aria-label="<?php echo esc_attr( $label ); ?>"
							>
								<?php if ( $s['icon'] ) : ?>
									<i data-lucide="<?php echo esc_attr( $s['icon'] ); ?>" aria-hidden="true"></i>
								<?php else : ?>
									<?php echo esc_html( $label ); ?>
								<?php endif; ?>
							</a>
						<?php endforeach; ?>
					</nav>
				<?php endif; ?>

			</div>

			<!-- Form column -->
			<div class="contact__form">
				<?php get_template_part( 'template-parts/contact-form' ); ?>
			</div>

		</div>
	</section>

</main>

<?php get_footer(); ?>
