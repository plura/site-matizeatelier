export function mtzInitHome() {
	const { gsap, ScrollTrigger, SplitText } = window;

	// Hold: units each item stays visible before transitioning.
	// Trans: units for the cross-fade / slide.
	// VH: scroll distance (viewport-heights) per timeline unit.
	const HOLD  = 2;
	const TRANS = 1;
	const VH    = 80;

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

		// Pull the next section up so it sits directly behind this one —
		// the stage (z-index:2) covers it until the stage itself exits.
		section.style.setProperty( '--mtz-anim-pull', `-${ scrollVh }vh` );

		// SplitText requires fonts to be loaded before splitting.
		document.fonts.ready.then( () => {
			// Split each headline into words for the staggered reveal.
			const wordSets = items.map( item => {
				const h = item.querySelector( '.home-statement__headline' );
				return h ? SplitText.create( h, { type: 'words' } ).words : [];
			} );

			// Non-first items: words start offset below the baseline
			// (the container is already opacity:0, so they're invisible —
			// this just sets their entrance position).
			for ( let i = 1; i < items.length; i++ ) {
				if ( wordSets[ i ]?.length ) gsap.set( wordSets[ i ], { y: 16 } );
			}

			const tl = gsap.timeline( { paused: true } );

			for ( let i = 1; i < items.length; i++ ) {
				const t     = ( i - 1 ) * ( HOLD + TRANS ) + HOLD;
				const words = wordSets[ i ];

				// Exit: container slides up and fades (words move with it)
				tl.to( items[ i - 1 ], { opacity: 0, y: -16, duration: TRANS, ease: 'power2.inOut' }, t );

				// Enter: container fades in while words stagger up from below
				tl.to( items[ i ], { opacity: 1, duration: TRANS, ease: 'power2.out' }, t );
				if ( words?.length ) {
					tl.to( words, {
						y:        0,
						stagger:  ( TRANS * 0.6 ) / words.length,
						duration: TRANS * 0.4,
						ease:     'power2.out',
					}, t );
				}
			}

			ScrollTrigger.create( {
				trigger:   section,
				start:     'top top',
				end:       `+=${ scrollVh }vh`,
				animation: tl,
				scrub:     0.5,
			} );
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

		// Exit last card so the stage is clear as it scrolls off
		const exitT = ( moodItems.length - 1 ) * ( HOLD + TRANS ) + HOLD;
		moodTl.to( moodItems[ moodItems.length - 1 ], {
			x: -80, opacity: 0, duration: TRANS, ease: 'power2.inOut',
		}, exitT );

		moodSection.style.height = `${ 100 + scrollVh }vh`;
		moodSection.style.setProperty( '--mtz-anim-pull', `-${ scrollVh }vh` );

		ScrollTrigger.create( {
			trigger:   moodSection,
			start:     'top top',
			end:       `+=${ scrollVh }vh`,
			animation: moodTl,
			scrub:     0.5,
		} );
	}
}
