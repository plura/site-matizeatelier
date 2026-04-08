<?php
$social_group  = get_field( 'mtz_social',  'option' ) ?: [];
$contact_group = get_field( 'mtz_contact', 'option' ) ?: [];

$social = [
	'Instagram' => $social_group['mtz_social_instagram'] ?? '',
	'Facebook'  => $social_group['mtz_social_facebook']  ?? '',
	'Pinterest' => $social_group['mtz_social_pinterest']  ?? '',
	'LinkedIn'  => $social_group['mtz_social_linkedin']   ?? '',
];

$contact_email   = $contact_group['mtz_contact_email']   ?? '';
$contact_phone   = $contact_group['mtz_contact_phone']   ?? '';
$contact_address = $contact_group['mtz_contact_address'] ?? '';
?>

<footer id="site-footer" class="site-footer">
	<div class="site-footer__inner">

		<div class="site-footer__brand">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php bloginfo( 'name' ); ?>">
				<?php bloginfo( 'name' ); ?>
			</a>
		</div>

		<?php if ( $contact_email || $contact_phone || $contact_address ) : ?>
			<address class="site-footer__contact">
				<?php if ( $contact_email ) : ?>
					<a href="mailto:<?php echo esc_attr( $contact_email ); ?>" class="site-footer__email">
						<?php echo esc_html( $contact_email ); ?>
					</a>
				<?php endif; ?>
				<?php if ( $contact_phone ) : ?>
					<a href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', $contact_phone ) ); ?>" class="site-footer__phone">
						<?php echo esc_html( $contact_phone ); ?>
					</a>
				<?php endif; ?>
				<?php if ( $contact_address ) : ?>
					<p class="site-footer__address">
						<?php echo wp_kses( $contact_address, [ 'br' => [] ] ); ?>
					</p>
				<?php endif; ?>
			</address>
		<?php endif; ?>

		<?php $social_filtered = array_filter( $social ); ?>
		<?php if ( $social_filtered ) : ?>
			<nav class="site-footer__social" aria-label="<?php esc_attr_e( 'Redes sociais', 'matize' ); ?>">
				<?php foreach ( $social_filtered as $label => $url ) : ?>
					<a
						href="<?php echo esc_url( $url ); ?>"
						class="site-footer__social-link"
						target="_blank"
						rel="noopener noreferrer"
						aria-label="<?php echo esc_attr( $label ); ?>"
					><?php echo esc_html( $label ); ?></a>
				<?php endforeach; ?>
			</nav>
		<?php endif; ?>

		<nav class="site-footer__nav" aria-label="<?php esc_attr_e( 'Menu rodapé', 'matize' ); ?>">
			<?php
			wp_nav_menu( [
				'theme_location' => 'footer',
				'container'      => false,
				'menu_class'     => 'site-footer__list',
			] );
			?>
		</nav>

		<p class="site-footer__copy">
			&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>
		</p>

	</div>
</footer>

<?php get_template_part( 'template-parts/cta' ); ?>

<?php wp_footer(); ?>
</body>
</html>
