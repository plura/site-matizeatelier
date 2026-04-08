<?php

add_shortcode( 'matize-hero-video', function () {

	$video = get_field( 'mtz_hero_video' );

	if ( ! $video ) return '';

	return sprintf(
		'<video class="hero__video" src="%s" autoplay muted loop playsinline aria-hidden="true"></video>',
		esc_url( $video['url'] )
	);

} );
