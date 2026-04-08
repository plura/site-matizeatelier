<?php

add_shortcode( 'matize-hero-video', function () {

	$video = get_field( 'hero_video' );

	if ( ! $video ) return '';

	return sprintf(
		'<video class="hero__video" src="%s" autoplay muted loop playsinline aria-hidden="true"></video>',
		esc_url( $video['url'] )
	);

} );
