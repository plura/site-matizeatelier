export function mtzInitHome() {
	const { gsap, ScrollTrigger } = window;

	// Shared pacing: each item holds for HOLD units before a TRANS-unit cross-fade.
	// 1 timeline unit ≈ 60 vh of scroll, so hold = 120 vh, transition = 60 vh.
	const HOLD  = 2;
	const TRANS = 1;

	function sectionScrollVh( itemCount ) {
		const tlDuration = ( itemCount - 1 ) * ( HOLD + TRANS ) + TRANS;
		return tlDuration * 60;
	}

	// ── Statement ─────────────────────────────────────────────────────────────
	const stage = document.querySelector( '.home-statement__stage' );
	const items = stage ? [ ...stage.querySelectorAll( '.home-statement__item' ) ] : [];

	if ( stage && items.length > 1 ) {
		const section  = stage.closest( '.home-statement' );
		const scrollVh = sectionScrollVh( items.length );

		section.style.height = `${ 100 + scrollVh }vh`;

		const tl = gsap.timeline( { paused: true } );
		for ( let i = 1; i < items.length; i++ ) {
			const t = ( i - 1 ) * ( HOLD + TRANS ) + HOLD;
			tl.to( items[ i - 1 ], { opacity: 0, duration: TRANS, ease: 'power2.inOut' }, t )
			  .to( items[ i ],     { opacity: 1, duration: TRANS, ease: 'power2.inOut' }, t );
		}

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
		const scrollVh = sectionScrollVh( moodItems.length );

		// Physical deck: back cards offset bottom-right at decreasing opacity.
		// z-index keeps front card on top regardless of DOM order.
		const STACK_X = 10; // px per layer
		const STACK_Y =  7; // px per layer

		function deckOpacity( layerIndex ) {
			return layerIndex === 0 ? 1 : Math.max( 0.3, 1 - layerIndex * 0.28 );
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
