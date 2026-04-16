export function mtzInitHome() {
	const { gsap, ScrollTrigger } = window;

	// ── Statement ─────────────────────────────────────────────────────────────
	const stage = document.querySelector( '.home-statement__stage' );
	const items = stage ? [ ...stage.querySelectorAll( '.home-statement__item' ) ] : [];

	if ( stage && items.length > 1 ) {
		const section = stage.closest( '.home-statement' );

		// Section height: stage (100vh) + 100vh per statement for scroll distance.
		// CSS sticky keeps the stage fixed while the section scrolls past it.
		section.style.height = `${ ( items.length + 1 ) * 100 }vh`;

		// Timeline: item[0] → item[1] → item[2] …
		const tl = gsap.timeline( { paused: true } );
		for ( let i = 1; i < items.length; i++ ) {
			tl.to( items[ i - 1 ], { opacity: 0, duration: 1, ease: 'power2.inOut' },        i - 1 )
			  .to( items[ i ],     { opacity: 1, duration: 1, ease: 'power2.inOut' }, i - 0.5 );
		}

		ScrollTrigger.create( {
			trigger:   section,
			start:     'top top',
			end:       `+=${ items.length * 100 }vh`,
			animation: tl,
			scrub:     0.5,
		} );
	}

	// ── Mood gallery ──────────────────────────────────────────────────────────
	const moodSection = document.querySelector( '.mood-gallery' );
	const moodDeck    = moodSection?.querySelector( '.mood-gallery__deck' );
	const moodItems   = moodDeck ? [ ...moodDeck.querySelectorAll( '.mood-gallery__item' ) ] : [];

	if ( moodSection && moodDeck && moodItems.length > 1 ) {
		moodSection.style.height = `${ ( moodItems.length + 1 ) * 100 }vh`;

		const moodTl = gsap.timeline( { paused: true } );
		for ( let i = 1; i < moodItems.length; i++ ) {
			moodTl.to( moodItems[ i - 1 ], { opacity: 0, duration: 1, ease: 'power2.inOut' },        i - 1 )
			      .to( moodItems[ i ],     { opacity: 1, duration: 1, ease: 'power2.inOut' }, i - 0.5 );
		}

		ScrollTrigger.create( {
			trigger:   moodSection,
			start:     'top top',
			end:       `+=${ moodItems.length * 100 }vh`,
			animation: moodTl,
			scrub:     0.5,
		} );
	}
}
