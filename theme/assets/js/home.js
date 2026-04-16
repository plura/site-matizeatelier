export function mtzInitHome() {
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
export function mtzInitStatements() {
	initScrollSection(
		document.querySelector( '.home-statement' ),
		'.home-statement__item',
		( gsap, items, tl ) => {
			for ( let i = 1; i < items.length; i++ ) {
				tl.to( items[ i - 1 ], { opacity: 0, duration: 1 }, '+=1' )
				  .to( items[ i ],     { opacity: 1, duration: 1 }, '<' );
			}
		}
	);
}

// ── Mood gallery ──────────────────────────────────────────────────────────────
export function mtzInitMood() {
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
