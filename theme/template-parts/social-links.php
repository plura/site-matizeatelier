<?php
/**
 * Social links component.
 * Data from ACF options via mtz_option().
 */

$social_group = mtz_option( 'mtz_social' );
$social = [
	'Instagram' => [ 'url' => $social_group['mtz_social_instagram'] ?? '', 'icon' => 'instagram' ],
	'Facebook'  => [ 'url' => $social_group['mtz_social_facebook']  ?? '', 'icon' => 'facebook'  ],
	'Pinterest' => [ 'url' => $social_group['mtz_social_pinterest'] ?? '', 'icon' => null        ],
	'LinkedIn'  => [ 'url' => $social_group['mtz_social_linkedin']  ?? '', 'icon' => 'linkedin'  ],
];

$social = array_filter( $social, fn( $s ) => ! empty( $s['url'] ) );

if ( ! $social ) return;
?>

<nav class="social-links" aria-label="<?php esc_attr_e( 'Redes sociais', 'matize' ); ?>">
	<?php foreach ( $social as $label => $s ) : ?>
		<a
			href="<?php echo esc_url( $s['url'] ); ?>"
			class="social-links__item"
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
