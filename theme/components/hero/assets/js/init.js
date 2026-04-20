gsap.registerPlugin( ScrollToPlugin );

document.addEventListener( 'DOMContentLoaded', () => {

	const hero         = document.querySelector( '.plura-wp-component .hero' );
	const lettersPath  = hero?.querySelector( '#mtz-logo-matize-letters' );
	const dot          = hero?.querySelector( '#mtz-logo-matize-dot' );
	const atelierPaths = [ ...( hero?.querySelectorAll( '#mtz-logo-atelier :is(path, rect, polygon)' ) ?? [] ) ];
	const video        = hero?.querySelector( '.hero__video' );
	const scrollBtn    = hero?.querySelector( '.hero__scroll' );

	if ( !hero || !lettersPath ) return;

	if ( scrollBtn ) {
		scrollBtn.addEventListener( 'click', ( e ) => {
			const target = hero.closest( '.plura-wp-component' )?.nextElementSibling;
			if ( target ) { e.preventDefault(); gsap.to( window, { scrollTo: target, duration: 1.2, ease: 'power2.inOut' } ); }
		} );
	}

	if ( video ) { video.pause(); video.currentTime = 0; }

	if ( new URLSearchParams( location.search ).has( 'test' ) ) {
		if ( video ) { video.play(); video.style.opacity = '1'; }
		hero.classList.add( 'is-intro-done' );
		return;
	}

	const length = lettersPath.getTotalLength() || 3000;

	// GSAP owns all initial states — no raw element.style manipulation
	gsap.set( lettersPath,  { strokeDasharray: length, strokeDashoffset: length, strokeOpacity: 1 } );
	gsap.set( dot,          { scale: 0, transformOrigin: 'center center' } );
	gsap.set( atelierPaths, { opacity: 0, y: ( i ) => i % 2 === 0 ? 8 : -8 } );

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
		opacity:  1,
		scale:    1,
		duration: 0.5,
		ease:     'back.out(2)',
	}, '-=0.4' );

	// 3. Atelier letters — alternate slide up/down into place
	tl.to( atelierPaths, {
		opacity:  1,
		y:        0,
		duration: 0.5,
		stagger:  0.07,
		ease:     'power2.out',
	}, '-=0.1' );

	// 4. Hold the complete logo for a beat, then reveal video
	tl.add( () => {
		hero.classList.add( 'is-intro-done' );
		if ( video ) video.play();
	}, '+=1' );

} );
