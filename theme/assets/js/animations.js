// Matize — animations

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
