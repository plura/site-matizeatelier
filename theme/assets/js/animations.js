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

// ── Page title reveal ─────────────────────────────────────────────────────────
export function mtzAnimPageTitle( target ) {
	if ( ! target ) return;

	document.fonts.ready.then( () => {
		const split = SplitText.create( target, { type: 'words' } );
		gsap.from( split.words, {
			y:        24,
			autoAlpha: 0,
			stagger:   0.1,
			ease:     'power2.out',
			duration:  0.7,
		} );
	} );
}
