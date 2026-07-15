export function mtzInitHome() {
	mtzInitStatements();
	mtzInitMood();
	mtzInitServices();
}

// ── Statements ────────────────────────────────────────────────────────────────
// Drives a background colour tween + a per-word stagger alongside the
// crossfade, on a fixed-duration timeline.
function mtzInitStatements() {
	const { gsap, ScrollTrigger } = window;

	const section = document.querySelector( '.home-statement' );
	const stage   = section?.querySelector( '.home-statement__stage' );
	const items   = [ ...( stage?.querySelectorAll( '.home-statement__item' ) ?? [] ) ];
	if ( !section || !stage || items.length < 2 ) return;

	const drift = 48;

	// Wrap each word of the incoming headlines in its own span for the stagger.
	items.slice( 1 ).forEach( ( item ) => {
		const headline = item.querySelector( '.home-statement__headline' );
		if ( !headline ) return;
		const words = headline.textContent.trim().split( /\s+/ );
		headline.textContent = '';
		words.forEach( ( word, i ) => {
			const span = document.createElement( 'span' );
			span.className = 'home-statement__word';
			span.textContent = word;
			headline.append( span, i < words.length - 1 ? ' ' : '' );
		} );
	} );

	const rootStyle = getComputedStyle( document.documentElement );
	const bgColors  = [ 'gold', 'teal', 'coral' ].map(
		( name ) => rootStyle.getPropertyValue( `--mtz-color-${ name }` ).trim()
	);

	gsap.set( items[ 0 ], { autoAlpha: 1, y: 0 } );
	gsap.set( items.slice( 1 ), { autoAlpha: 0, y: 0 } );
	gsap.set( stage, { backgroundColor: bgColors[ 0 ] } );

	const tl = gsap.timeline();

	for ( let i = 1; i < items.length; i++ ) {
		const prev    = items[ i - 1 ];
		const curr    = items[ i ];
		const words   = curr.querySelectorAll( '.home-statement__word' );
		const tagline = curr.querySelector( '.home-statement__tagline' );
		const start   = 1.0 + ( i - 1 ) * 2.5;

		tl.to( prev, { autoAlpha: 0, y: -drift, duration: 0.8, ease: 'none' }, start )
		  .to( stage, { backgroundColor: bgColors[ i ] ?? bgColors[ bgColors.length - 1 ], duration: 1.4, ease: 'none' }, start )
		  .set( curr, { autoAlpha: 1 }, start + 0.5 )
		  .fromTo( words, { autoAlpha: 0, y: drift }, { autoAlpha: 1, y: 0, duration: 0.7, stagger: 0.09, ease: 'none' }, start + 0.5 );

		if ( tagline ) {
			tl.fromTo( tagline, { autoAlpha: 0, y: drift * 0.6 }, { autoAlpha: 1, y: 0, duration: 0.6, ease: 'none' }, start + 1.0 );
		}
	}
	tl.to( {}, { duration: 0.9 } );

	// Idempotent: guard against double-init killing/duplicating a live trigger.
	ScrollTrigger.getById( 'mtz-statements' )?.kill();

	ScrollTrigger.create( {
		id:      'mtz-statements',
		trigger: section,
		start:   'top top',
		end:     'bottom bottom',
		scrub:   0.6,
		snap: {
			snapTo:   [ 0, 0.5, 1 ],
			duration: { min: 0.2, max: 0.6 },
			delay:    0.15,
			ease:     'power1.inOut',
		},
		animation: tl,
	} );
}

// ── Mood gallery ──────────────────────────────────────────────────────────────
// Each card gets a fixed resting tilt/offset (deck feel), then flies in from
// off-screen right in a staggered entrance. A progress counter/track advances
// from ScrollTrigger's onUpdate, matching the "~55% through a card's tween"
// threshold from the reference logic.
function mtzInitMood() {
	const { gsap, ScrollTrigger } = window;

	const section = document.querySelector( '.mood-gallery' );
	const stack   = section?.querySelector( '.mood-gallery__deck' );
	const items   = [ ...( stack?.querySelectorAll( '.mood-gallery__item' ) ?? [] ) ];
	if ( !section || !stack || items.length < 2 ) return;

	const counter = section.querySelector( '.mood-gallery__counter' );
	const fill    = section.querySelector( '.mood-gallery__fill' );

	const ROTATIONS = [ -4, 3, -2.5, 4.5, -3.5, 2 ];
	const OFFSETS   = [ { x: -10, y: -8 }, { x: 14, y: 6 }, { x: -16, y: 12 }, { x: 10, y: -14 }, { x: -6, y: 10 }, { x: 12, y: -6 } ];

	items.forEach( ( item, i ) => {
		const offset = OFFSETS[ i % OFFSETS.length ];
		gsap.set( item, {
			xPercent: -50 + offset.x * 0.4,
			yPercent: -50 + offset.y * 0.4,
			rotation: ROTATIONS[ i % ROTATIONS.length ],
			zIndex:   i + 1,
		} );
	} );

	const tl = gsap.timeline();

	items.forEach( ( item, i ) => {
		if ( i === 0 ) return;
		const rotation = ROTATIONS[ i % ROTATIONS.length ];
		tl.fromTo( item,
			{
				x:        () => window.innerWidth - stack.getBoundingClientRect().left - stack.offsetWidth * 0.5 + item.offsetWidth,
				rotation: rotation + 14,
			},
			{ x: 0, rotation, duration: 1, ease: 'power2.out' },
			( i - 1 ) * 1.15
		);
	} );
	tl.to( {}, { duration: 0.6 } );

	// Mirrors the timeline's own pacing: card i's tween lands ~55% in around
	// position (i-1)*1.15 + 0.55, normalized against the full timeline length.
	const timelineLength = ( items.length - 1 ) * 1.15 + 0.6;
	const updateProgress = ( progress ) => {
		let idx = 1;
		for ( let i = 1; i < items.length; i++ ) {
			if ( progress >= ( ( i - 1 ) * 1.15 + 0.55 ) / timelineLength ) idx = i + 1;
		}
		if ( counter ) counter.textContent = `${ String( idx ).padStart( 2, '0' ) } / ${ String( items.length ).padStart( 2, '0' ) }`;
		if ( fill ) gsap.set( fill, { scaleX: idx / items.length } );
	};
	updateProgress( 0 );

	// Idempotent: guard against double-init killing/duplicating a live trigger.
	ScrollTrigger.getById( 'mtz-mood' )?.kill();

	ScrollTrigger.create( {
		id:        'mtz-mood',
		trigger:   section,
		start:     'top top',
		end:       'bottom bottom',
		scrub:     0.6,
		animation: tl,
		onUpdate:  ( self ) => updateProgress( self.progress ),
	} );
}

// ── Services ──────────────────────────────────────────────────────────────────
// The sticky stack itself is pure CSS (see home.css) — this only plays each
// card's one-shot entry, time-based rather than scrubbed. Card 1 has no
// scroll runway above it (already pinned at load), so it plays once on load;
// cards 2+ play/reverse off their own ScrollTrigger.
function mtzInitServices() {
	const { gsap, ScrollTrigger } = window;

	const cards = [ ...document.querySelectorAll( '.home-services__grid .plura-wp-post' ) ];
	if ( !cards.length ) return;

	cards.forEach( ( card, i ) => {
		const num   = card.querySelector( '.home-services__number' );
		const txt   = [ ...card.querySelectorAll( '.home-services__text > *' ) ];
		const photo = card.querySelector( '.home-services__photo' );
		const rest  = i % 2 === 0 ? 2.5 : -2.5;

		const build = ( tl ) => {
			tl.fromTo( num, { y: 160, xPercent: -12, autoAlpha: 0 }, { y: 0, xPercent: 0, autoAlpha: 1, duration: 0.7, ease: 'power2.out' }, 0 )
			  .fromTo( txt, { y: 70, autoAlpha: 0 }, { y: 0, autoAlpha: 1, duration: 0.6, stagger: 0.16, ease: 'power2.out' }, 0.18 );
			if ( photo ) {
				tl.fromTo( photo, { rotation: rest * 7, scale: 1.28, y: 140, autoAlpha: 0.4 }, { rotation: rest, scale: 1, y: 0, autoAlpha: 1, duration: 0.9, ease: 'power3.out' }, 0.05 );
			}
		};

		if ( i === 0 ) {
			build( gsap.timeline( { delay: 0.15 } ) );
			return;
		}

		// Idempotent: guard against double-init killing/duplicating a live trigger.
		ScrollTrigger.getById( `mtz-service-${ i }` )?.kill();

		build( gsap.timeline( {
			scrollTrigger: {
				id:            `mtz-service-${ i }`,
				trigger:       card,
				start:         'top 45%',
				toggleActions: 'play none none reverse',
			},
		} ) );
	} );
}
