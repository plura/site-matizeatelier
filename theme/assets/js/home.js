export function mtzInitHome() {
	mtzInitStatements();
	mtzInitMood();
}

// ── Statements ────────────────────────────────────────────────────────────────
export function mtzInitStatements() {
	const { gsap, ScrollTrigger } = window;

	const section = document.querySelector( '.home-statement' );
	const items   = [ ...( section?.querySelectorAll( '.home-statement__item' ) ?? [] ) ];
	if ( !section || items.length < 2 ) return;

	// One viewport per item — entry and exit breathing room live in the timeline
	section.style.height = `${ items.length * 100 }vh`;

	const tl = gsap.timeline();
	tl.set( {}, {}, '+=1' ); // entry hold — section has settled before first transition
	for ( let i = 1; i < items.length; i++ ) {
		tl.to( items[ i - 1 ], { opacity: 0, duration: 1 }, '+=1' )
		  .to( items[ i ],     { opacity: 1, duration: 1 }, '<' );
	}
	tl.set( {}, {}, '+=1' ); // exit hold — last item lingers before section scrolls off

	ScrollTrigger.create( {
		trigger:    section,
		start:      'top top',
		end:        'bottom bottom',
		scrub:      1,
		animation:  tl,
	} );
}

// ── Mood gallery ──────────────────────────────────────────────────────────────
export function mtzInitMood() {
	const { gsap, ScrollTrigger } = window;

	const section = document.querySelector( '.mood-gallery' );
	const items   = [ ...( section?.querySelectorAll( '.mood-gallery__item' ) ?? [] ) ];
	if ( !section || items.length < 2 ) return;

	// One viewport per item — entry and exit breathing room live in the timeline
	section.style.height = `${ items.length * 100 }vh`;

	// Depth offsets — front card is always straight; background cards tilt slightly
	const STEP      = { x: 7, y: 5 };
	const DEPTH_ROT = [ 0, 0.4, 0.7, 0.5, 0.6 ];

	// All cards start off-screen right and invisible — z-index pre-assigned so each
	// arriving card is already above the existing stack when it slides in
	items.forEach( ( item, i ) => gsap.set( item, { x: '110%', y: 0, opacity: 0, zIndex: items.length + i } ) );

	// Each card arrives from the right to the front;
	// existing cards shift back one depth level and pick up a slight rotation
	const tl = gsap.timeline();
	tl.set( {}, {}, '+=1' ); // entry hold
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
	tl.set( {}, {}, '+=1' ); // exit hold — last card lingers before section scrolls off

	ScrollTrigger.create( {
		trigger:   section,
		start:     'top top',
		end:       'bottom bottom',
		scrub:     1,
		animation: tl,
	} );
}
