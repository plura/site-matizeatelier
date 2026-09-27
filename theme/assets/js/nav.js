// Handles hamburger open/close, plus outside-click and Esc dismissal, on mobile.
function mtzNavToggle() {
	const toggle = document.querySelector( '.site-header__menu-toggle' );
	const nav    = document.querySelector( '#site-nav' );

	if ( ! toggle || ! nav ) return;

	const isOpen  = () => nav.classList.contains( 'is-open' );
	const setOpen = ( open ) => {
		nav.classList.toggle( 'is-open', open );
		toggle.setAttribute( 'aria-expanded', open );
	};

	toggle.addEventListener( 'click', () => setOpen( ! isOpen() ) );

	document.addEventListener( 'click', ( e ) => {
		if ( isOpen() && ! nav.contains( e.target ) && ! toggle.contains( e.target ) ) setOpen( false );
	} );

	document.addEventListener( 'keydown', ( e ) => {
		if ( e.key === 'Escape' && isOpen() ) {
			setOpen( false );
			toggle.focus();
		}
	} );
}

// Appends a sliding underline indicator to `list` that follows hovered items
// and snaps back to `active` on mouse-out. Call once per group (main nav, WPML).
function createIndicator( list, items, active ) {
	if ( ! items.length ) return;

	const indicator = document.createElement( 'span' );
	indicator.className = 'site-nav__indicator';
	indicator.setAttribute( 'aria-hidden', 'true' );
	list.appendChild( indicator );

	const moveTo = ( el, duration = 0.25 ) => gsap.to( indicator, {
		x: el.offsetLeft, width: el.offsetWidth, duration, ease: 'power2.out',
	} );

	let current = active ?? null;

	const snapToCurrent = () => {
		if ( current ) gsap.set( indicator, { x: current.offsetLeft, width: current.offsetWidth } );
	};

	gsap.set( indicator, { opacity: current ? 1 : 0 } );
	snapToCurrent();

	items.forEach( el => {
		el.addEventListener( 'mouseenter', () => {
			gsap.set( indicator, { opacity: 1 } );
			moveTo( el );
		} );

		el.addEventListener( 'click', () => {
			items.forEach( i => i.classList.remove( 'mtz-active' ) );
			el.classList.add( 'mtz-active' );
			current = el;
		} );
	} );

	list.addEventListener( 'mouseleave', () => {
		if ( current ) moveTo( current );
		else gsap.to( indicator, { opacity: 0, duration: 0.2 } );
	} );

	// Offsets also shift once the web fonts swap in (font-display: swap).
	window.addEventListener( 'resize', snapToCurrent );
	document.fonts.ready.then( snapToCurrent );
}

// Entry point — wires up mobile toggle and sliding indicators for both
// the main nav items and the WPML language switcher items.
export function mtzInitNav() {
	mtzNavToggle();

	const list = document.querySelector( '.site-nav__list' );
	if ( ! list || typeof gsap === 'undefined' ) return;

	list.style.position = 'relative';

	createIndicator(
		list,
		[ ...list.querySelectorAll( 'li:not(.wpml-ls-item) > a' ) ],
		list.querySelector( 'li.current-menu-item:not(.wpml-ls-item) > a' )
	);

	createIndicator(
		list,
		[ ...list.querySelectorAll( '.wpml-ls-item > a' ) ],
		list.querySelector( '.wpml-ls-current-language > a' )
	);
}
