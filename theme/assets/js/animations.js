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
		const split = SplitText.create( target, { type: 'chars' } );
		gsap.set( split.chars, {
			yPercent: () => Math.random() > 0.5 ? 110 : -110,
			opacity:  0,
		} );
		gsap.set( target, { visibility: 'visible' } );
		gsap.to( split.chars, {
			yPercent: 0,
			opacity:  1,
			duration: 0.7,
			ease:     'power3.out',
			stagger:  { each: 0.03, from: 'random' },
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
