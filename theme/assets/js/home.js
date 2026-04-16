// vh of scroll dedicated to each item (hold + fade combined). Only knob to tune.
const PER_ITEM = 200;

// ── Statements ────────────────────────────────────────────────────────────────
export function mtzInitStatements() {
	const { gsap, ScrollTrigger } = window;

	const section = document.querySelector( '.home-statement' );
	const items   = [ ...( section?.querySelectorAll( '.home-statement__item' ) ?? [] ) ];
	if ( !section || items.length < 2 ) return;

	const scrollVh = ( items.length - 1 ) * PER_ITEM;

	section.style.height       = `${ 100 + scrollVh }vh`;
	section.style.marginBottom = `-${ scrollVh }vh`;

	const tl = gsap.timeline( { paused: true } );
	for ( let i = 1; i < items.length; i++ ) {
		tl.to( items[ i - 1 ], { opacity: 0, duration: 1 }, i - 1 )
		  .to( items[ i ],     { opacity: 1, duration: 1 }, i - 0.5 );
	}

	ScrollTrigger.create( {
		trigger:   section,
		start:     'top top',
		end:       `+=${ scrollVh }vh`,
		animation: tl,
		scrub:     0.5,
	} );
}

// ── Mood gallery ──────────────────────────────────────────────────────────────
export function mtzInitMood() {
	const { gsap, ScrollTrigger } = window;

	const section = document.querySelector( '.mood-gallery' );
	const deck    = section?.querySelector( '.mood-gallery__deck' );
	const items   = [ ...( deck?.querySelectorAll( '.mood-gallery__item' ) ?? [] ) ];
	if ( !section || !deck || items.length < 2 ) return;

	const scrollVh = ( items.length - 1 ) * PER_ITEM;

	const STACK_X = 10;
	const STACK_Y =  7;
	const opacity = i => ( i === 0 ? 1 : Math.max( 0.3, 1 - i * 0.28 ) );

	items.forEach( ( item, i ) => gsap.set( item, {
		x: i * STACK_X, y: i * STACK_Y, opacity: opacity( i ), zIndex: items.length - i,
	} ) );

	const tl = gsap.timeline( { paused: true } );
	for ( let i = 1; i < items.length; i++ ) {
		tl.to( items[ i - 1 ], { x: -80, opacity: 0, duration: 1 }, i - 1 );
		for ( let j = i; j < items.length; j++ ) {
			tl.to( items[ j ], {
				x: ( j - 1 ) * STACK_X, y: ( j - 1 ) * STACK_Y, opacity: opacity( j - 1 ), duration: 1,
			}, i - 1 );
		}
	}

	section.style.height       = `${ 100 + scrollVh }vh`;
	section.style.marginBottom = `-${ scrollVh }vh`;

	ScrollTrigger.create( {
		trigger:   section,
		start:     'top top',
		end:       `+=${ scrollVh }vh`,
		animation: tl,
		scrub:     0.5,
	} );
}
