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

	// Simple fade sequence — no scrub, plays in real time when section enters view
	const tl = gsap.timeline( { paused: true } );
	for ( let i = 1; i < items.length; i++ ) {
		tl.to( items[ i - 1 ], { opacity: 0, duration: 1 }, '+=1' )
		  .to( items[ i ],     { opacity: 1, duration: 1 }, '<' );
	}
	tl.to( items[ items.length - 1 ], { opacity: 0, duration: 1 }, '+=1' );

	ScrollTrigger.create( {
		trigger:     section,
		start:       'top top',
		onEnter:     () => tl.play(),
		onLeaveBack: () => tl.progress( 0 ).pause(),
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

	const tl = gsap.timeline( { paused: true } );
	for ( let i = 1; i < items.length; i++ ) {
		tl.to( items[ i - 1 ], { opacity: 0, duration: 1 }, '+=1' )
		  .to( items[ i ],     { opacity: 1, duration: 1 }, '<' );
	}
	tl.to( items[ items.length - 1 ], { opacity: 0, duration: 1 }, '+=1' );

	ScrollTrigger.create( {
		trigger:     section,
		start:       'top top',
		onEnter:     () => tl.play(),
		onLeaveBack: () => tl.progress( 0 ).pause(),
	} );
}
