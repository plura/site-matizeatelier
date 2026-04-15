<?php
/**
 * Social links component.
 * Data from ACF options via mtz_option().
 * Brand icons as inline SVGs — Lucide dropped brand icons in v0.294+.
 */

$social_group = mtz_option( 'mtz_social' );

$social = [
	'Instagram' => [
		'url'  => $social_group['mtz_social_instagram'] ?? '',
		'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect width="20" height="20" x="2" y="2" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/></svg>',
	],
	'Facebook'  => [
		'url'  => $social_group['mtz_social_facebook'] ?? '',
		'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>',
	],
	'Pinterest' => [
		'url'  => $social_group['mtz_social_pinterest'] ?? '',
		'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 2C6.48 2 2 6.48 2 12c0 4.24 2.65 7.86 6.39 9.29-.09-.78-.17-1.98.03-2.83.18-.78 1.17-4.97 1.17-4.97s-.3-.6-.3-1.48c0-1.39.81-2.43 1.81-2.43.85 0 1.27.64 1.27 1.41 0 .86-.55 2.14-.83 3.33-.24 1 .5 1.81 1.48 1.81 1.77 0 3.14-1.87 3.14-4.57 0-2.39-1.72-4.06-4.16-4.06-2.84 0-4.5 2.13-4.5 4.33 0 .86.33 1.77.74 2.27a.3.3 0 0 1 .07.29c-.08.31-.24.99-.28 1.13-.04.18-.15.22-.34.13-1.25-.58-2.03-2.41-2.03-3.87 0-3.15 2.29-6.05 6.61-6.05 3.47 0 6.16 2.47 6.16 5.78 0 3.45-2.17 6.22-5.19 6.22-1.01 0-1.97-.53-2.29-1.15l-.62 2.38c-.23.87-.84 1.96-1.24 2.62.94.29 1.93.45 2.96.45C17.52 22 22 17.52 22 12S17.52 2 12 2z"/></svg>',
	],
	'LinkedIn'  => [
		'url'  => $social_group['mtz_social_linkedin'] ?? '',
		'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect width="4" height="12" x="2" y="9"/><circle cx="4" cy="4" r="2"/></svg>',
	],
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
			<?php echo $s['icon']; ?>
			<span class="social-links__username"><?php echo esc_html( mtz_social_username( $s['url'] ) ); ?></span>
		</a>
	<?php endforeach; ?>
</nav>
