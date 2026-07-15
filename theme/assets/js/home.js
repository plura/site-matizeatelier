export function mtzInitHome() {
	if ( window.mtzDev?.noIntro ) return;
	mtzInitStatements();
	mtzInitMood();
}

// ── Shared setup ──────────────────────────────────────────────────────────────
function initScrollSection( stage, itemSelector, buildTimeline, hold = true ) {
	const { gsap, ScrollTrigger } = window;

	const items = [ ...( stage?.querySelectorAll( itemSelector ) ?? [] ) ];
	if ( !stage || items.length < 2 ) return;

	stage.style.height = `${ items.length * 100 }vh`;

	const tl = gsap.timeline();
	if ( hold ) tl.set( {}, {}, '+=1' );
	buildTimeline( gsap, items, tl );
	if ( hold ) tl.set( {}, {}, '+=1' );

	ScrollTrigger.create( {
		trigger:   stage,
		start:     'top top',
		end:       'bottom bottom',
		scrub:     1,
		animation: tl,
	} );
}

// ── Statements ────────────────────────────────────────────────────────────────
// Bespoke (not initScrollSection): drives a background colour tween + a
// per-word stagger alongside the crossfade, on a fixed-duration timeline.
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
function mtzInitMood() {
	const STEP      = { x: 7, y: 5 };
	const DEPTH_ROT = [ 0, 0.4, 0.7, 0.5, 0.6 ];

	initScrollSection(
		document.querySelector( '.mood-gallery' ),
		'.mood-gallery__item',
		( gsap, items, tl ) => {
			items.forEach( ( item, i ) => gsap.set( item, { x: '110%', y: 0, opacity: 0, zIndex: items.length + i } ) );

			for ( let i = 0; i < items.length; i++ ) {
				tl.to( items[ i ], { x: 0, y: 0, opacity: 1, rotation: 0, duration: 1 }, '+=1' );
				for ( let j = 0; j < i; j++ ) {
					const depth = i - j;
					tl.to( items[ j ], {
						x:        depth * STEP.x,
						y:        depth * STEP.y,
						rotation: DEPTH_ROT[ Math.min( depth, DEPTH_ROT.length - 1 ) ],
						duration: 1,
					}, '<' );
				}
			}
		}
	);
}
