<?php
// Shortcodes for index.html — the parts a static component template can't
// hold: ACF data, translated strings, the shared icon helper.

// Hero video: ACF "Página Inicial → Hero → Vídeo" when set, else the original upload.
add_shortcode( 'matize-hero-video', function () {

	$video = ( get_field( 'mtz_home_hero' ) ?: [] )['mtz_hero_video'] ?? null;

	return sprintf(
		'<video class="hero__video" autoplay loop muted playsinline disablepictureinpicture aria-hidden="true"><source src="%s" type="%s"></video>',
		esc_url( $video['url'] ?? wp_get_upload_dir()['baseurl'] . '/2026/04/mtz-hero-20260411.webm' ),
		esc_attr( $video['mime_type'] ?? 'video/webm' )
	);

} );

// The logo is the visual title, but the home page otherwise has no <h1>.
add_shortcode( 'matize-hero-heading', fn() => '<h1 class="sr-only">' . esc_html( get_bloginfo( 'name' ) ) . '</h1>' );

add_shortcode( 'matize-hero-scroll', fn() => sprintf(
	'<a href="#main" class="hero__scroll" aria-label="%s">%s</a>',
	esc_attr__( 'Scroll to content', 'matize' ),
	mtz_icon( 'chevron-down' )
) );
