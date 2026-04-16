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

	// Tall enough to keep the stage pinned while the animation plays
	// 100vh stage + 100vh per item + 100vh exit room
	section.style.height = `${ ( items.length + 2 ) * 100 }vh`;

	const tl = gsap.timeline();
	for ( let i = 1; i < items.length; i++ ) {
		tl.to( items[ i - 1 ], { opacity: 0, duration: 1 }, '+=1' )
		  .to( items[ i ],     { opacity: 1, duration: 1 }, '<' );
	}
	tl.to( items[ items.length - 1 ], { opacity: 0, duration: 1 }, '+=1' );

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

	// Same logic as statements: 100vh stage + 100vh per item + 100vh exit room
	section.style.height = `${ ( items.length + 2 ) * 100 }vh`;

	// Depth offsets — front card is always straight; background cards tilt slightly
	const STEP     = { x: 7, y: 5 };
	const DEPTH_ROT = [ 0, -0.8, 0.6, -0.5, 0.4 ]; // rotation per depth level

	// item[0] starts at front; all others wait off-screen right
	gsap.set( items[ 0 ], { x: 0, y: 0, rotation: 0, zIndex: items.length } );
	items.slice( 1 ).forEach( item => gsap.set( item, { x: '110%', y: 0, zIndex: 0 } ) );

	// Each new card arrives from the right to the front;
	// existing cards shift back one depth level and pick up a slight rotation
	const tl = gsap.timeline();
	for ( let i = 1; i < items.length; i++ ) {
		tl.to( items[ i ], { x: 0, y: 0, rotation: 0, zIndex: items.length + i, duration: 1 }, '+=1' );
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
	tl.to( items[ items.length - 1 ], { opacity: 0, duration: 1 }, '+=1' );

	ScrollTrigger.create( {
		trigger:   section,
		start:     'top top',
		end:       'bottom bottom',
		scrub:     1,
		animation: tl,
	} );
}
