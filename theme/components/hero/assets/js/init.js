document.addEventListener( 'DOMContentLoaded', () => {

	const hero         = document.querySelector( '.plura-wp-component .hero' );
	const lettersPath  = hero?.querySelector( '#mtz-logo-matize-letters' );
	const dot          = hero?.querySelector( '#mtz-logo-matize-dot' );
	const atelierPaths = [ ...( hero?.querySelectorAll( '#mtz-logo-atelier path' ) ?? [] ) ];
	const video        = hero?.querySelector( '.hero__video' );

	if ( !hero || !lettersPath ) return;

	const length = lettersPath.getTotalLength() || 3000;

	// GSAP owns all initial states — no raw element.style manipulation
	gsap.set( lettersPath,  { strokeDasharray: length, strokeDashoffset: length } );
	gsap.set( dot,          { scale: 0, transformOrigin: 'center center' } );
	gsap.set( atelierPaths, { opacity: 0 } );

	const tl = gsap.timeline( { delay: 0.3 } );

	// 1. Draw on the MATIZE letters
	tl.to( lettersPath, {
		strokeDashoffset: 0,
		duration:         3,
		ease:             'power2.inOut',
	} );

	// 1b. Cross-fade: fill in, stroke out
	tl.to( lettersPath, {
		fillOpacity:   1,
		strokeOpacity: 0,
		duration:      0.4,
		ease:          'power1.inOut',
	} );

	// 2. Dot slide-pop — starts near end of letter draw
	tl.to( dot, {
		scale:    1,
		duration: 0.5,
		ease:     'back.out(2)',
	}, '-=0.4' );

	// 3. Atelier letters stagger in
	tl.to( atelierPaths, {
		opacity:  1,
		duration: 0.4,
		stagger:  0.06,
		ease:     'power1.out',
	}, '-=0.1' );

	// 4. Reveal video — add is-intro-done when it starts so the logo colour transitions simultaneously
	tl.add( () => {
		if ( video ) gsap.to( video, {
			opacity:  1,
			duration: 1.5,
			delay:    1,
			ease:     'power1.inOut',
			onStart:  () => hero.classList.add( 'is-intro-done' ),
		} );
	} );

} );
