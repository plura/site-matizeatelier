export function mtzInitHome() {
	const { gsap, ScrollTrigger } = window;

	// Each item holds for HOLD units, then cross-fades over TRANS units.
	// The last item also exits (same TRANS duration) so the stage is clear when
	// it scrolls off — otherwise the last item lingers while the stage exits.
	// 1 timeline unit = VH viewport-heights of scroll.
	const HOLD  = 2;  // units visible before transition
	const TRANS = 1;  // units for cross-fade
	const VH    = 80; // vh of scroll per timeline unit

	// Total scroll distance: N items × (HOLD + TRANS) units × VH
	// (last item's HOLD + exit TRANS = same slot as all others)
	function scrollVhFor( n ) {
		return n * ( HOLD + TRANS ) * VH;
	}

	// ── Statement ─────────────────────────────────────────────────────────────
	const stage = document.querySelector( '.home-statement__stage' );
	const items = stage ? [ ...stage.querySelectorAll( '.home-statement__item' ) ] : [];

	if ( stage && items.length > 1 ) {
		const section  = stage.closest( '.home-statement' );
		const scrollVh = scrollVhFor( items.length );

		section.style.height = `${ 100 + scrollVh }vh`;

		const tl = gsap.timeline( { paused: true } );

		for ( let i = 1; i < items.length; i++ ) {
			const t = ( i - 1 ) * ( HOLD + TRANS ) + HOLD;
			tl.to( items[ i - 1 ], { opacity: 0, duration: TRANS, ease: 'power2.inOut' }, t )
			  .to( items[ i ],     { opacity: 1, duration: TRANS, ease: 'power2.inOut' }, t );
		}

		// Exit the last item so the stage is blank as it scrolls off
		const exitT = ( items.length - 1 ) * ( HOLD + TRANS ) + HOLD;
		tl.to( items[ items.length - 1 ], { opacity: 0, duration: TRANS, ease: 'power2.inOut' }, exitT );

		ScrollTrigger.create( {
			trigger:   section,
			start:     'top top',
			end:       `+=${ scrollVh }vh`,
			animation: tl,
			scrub:     0.5,
		} );
	}

	// ── Mood gallery ──────────────────────────────────────────────────────────
	const moodSection = document.querySelector( '.mood-gallery' );
	const moodDeck    = moodSection?.querySelector( '.mood-gallery__deck' );
	const moodItems   = moodDeck ? [ ...moodDeck.querySelectorAll( '.mood-gallery__item' ) ] : [];

	if ( moodSection && moodDeck && moodItems.length > 1 ) {
		const scrollVh = scrollVhFor( moodItems.length );

		const STACK_X = 10;
		const STACK_Y =  7;

		function deckOpacity( i ) {
			return i === 0 ? 1 : Math.max( 0.3, 1 - i * 0.28 );
		}

		moodItems.forEach( ( item, i ) => {
			gsap.set( item, {
				x:       i * STACK_X,
				y:       i * STACK_Y,
				opacity: deckOpacity( i ),
				zIndex:  moodItems.length - i,
			} );
		} );

		const moodTl = gsap.timeline( { paused: true } );

		for ( let i = 1; i < moodItems.length; i++ ) {
			const t = ( i - 1 ) * ( HOLD + TRANS ) + HOLD;

			// Front card exits left
			moodTl.to( moodItems[ i - 1 ], {
				x: -80, opacity: 0, duration: TRANS, ease: 'power2.inOut',
			}, t );

			// All remaining cards shift one position forward in the deck
			for ( let j = i; j < moodItems.length; j++ ) {
				const target = j - 1;
				moodTl.to( moodItems[ j ], {
					x:        target * STACK_X,
					y:        target * STACK_Y,
					opacity:  deckOpacity( target ),
					duration: TRANS,
					ease:     'power2.inOut',
				}, t );
			}
		}

		// Exit the last item
		const exitT = ( moodItems.length - 1 ) * ( HOLD + TRANS ) + HOLD;
		moodTl.to( moodItems[ moodItems.length - 1 ], {
			x: -80, opacity: 0, duration: TRANS, ease: 'power2.inOut',
		}, exitT );

		moodSection.style.height = `${ 100 + scrollVh }vh`;

		ScrollTrigger.create( {
			trigger:   moodSection,
			start:     'top top',
			end:       `+=${ scrollVh }vh`,
			animation: moodTl,
			scrub:     0.5,
		} );
	}
}
