export function mtzInitHome() {
	mtzInitStatements();
	mtzInitMood();
}

// ── Shared setup ──────────────────────────────────────────────────────────────
function initScrollSection( stage, itemSelector, buildTimeline ) {
	const { gsap, ScrollTrigger } = window;

	const items = [ ...( stage?.querySelectorAll( itemSelector ) ?? [] ) ];
	if ( !stage || items.length < 2 ) return;

	stage.style.height = `${ items.length * 100 }vh`;

	ScrollTrigger.create( {
		trigger:   stage,
		start:     'top top',
		end:       'bottom bottom',
		scrub:     1,
		animation: buildTimeline( gsap, items ),
	} );
}

// ── Statements ────────────────────────────────────────────────────────────────
export function mtzInitStatements() {
	initScrollSection(
		document.querySelector( '.home-statement' ),
		'.home-statement__item',
		( gsap, items ) => {
			const tl = gsap.timeline();
			tl.set( {}, {}, '+=1' );
			for ( let i = 1; i < items.length; i++ ) {
				tl.to( items[ i - 1 ], { opacity: 0, duration: 1 }, '+=1' )
				  .to( items[ i ],     { opacity: 1, duration: 1 }, '<' );
			}
			tl.set( {}, {}, '+=1' );
			return tl;
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
		( gsap, items ) => {
			// All cards start off-screen right and invisible — z-index pre-assigned so each
			// arriving card is already above the existing stack when it slides in
			items.forEach( ( item, i ) => gsap.set( item, { x: '110%', y: 0, opacity: 0, zIndex: items.length + i } ) );

			const tl = gsap.timeline();
			tl.set( {}, {}, '+=1' );
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
			tl.set( {}, {}, '+=1' );
			return tl;
		}
	);
}
