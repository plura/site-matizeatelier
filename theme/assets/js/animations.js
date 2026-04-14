// Matize — animations

// ── Gallery items reveal ──────────────────────────────────────────────────────
export function mtzAnimGalleryItems( items ) {
	if ( ! items || ( items instanceof NodeList && ! items.length ) ) return;

	gsap.from( items, {
		autoAlpha: 0,
		y:         30,
		stagger:   0.08,
		ease:      'power2.out',
		scrollTrigger: {
			trigger: items[ 0 ]?.parentElement,
			start:   'top 80%',
		},
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
