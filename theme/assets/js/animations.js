// Matize — animations

gsap.registerPlugin( ScrollTrigger, SplitText );

// ── Gallery items reveal ──────────────────────────────────────────────────────
export function mtzAnimGalleryItems( items ) {
	if ( ! items || ( items instanceof NodeList && ! items.length ) ) return;

	ScrollTrigger.batch( items, {
		start: 'top 90%',
		onEnter: batch => gsap.from( batch, {
			autoAlpha: 0,
			y:         30,
			stagger:   0.1,
			ease:      'power2.out',
			duration:  0.6,
		} ),
	} );
}

// ── Page header reveal (title + optional intro) ───────────────────────────────
export function mtzAnimPageTitle() {
	const target = document.querySelector( '.page-header__title' );
	if ( ! target ) return;

	document.fonts.ready.then( () => {
		const split = SplitText.create( target, { type: 'words' } );
		gsap.set( target, { visibility: 'visible' } );
		gsap.from( split.words, {
			y:        20,
			opacity:  0,
			duration: 0.6,
			ease:     'power2.out',
			stagger:  0.08,
		} );

		const intro = target.closest( '.page-header' )?.querySelector( '.page-intro' );
		if ( intro ) {
			gsap.from( intro, {
				autoAlpha: 0,
				y:         16,
				duration:  0.6,
				delay:     0.4,
				ease:      'power2.out',
			} );
		}
	} );
}
