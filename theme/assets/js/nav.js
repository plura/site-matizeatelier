// Handles hamburger open/close and outside-click dismissal on mobile.
function mtzNavToggle() {
	const toggle = document.querySelector( '.site-header__menu-toggle' );
	const nav    = document.querySelector( '#site-nav' );

	if ( ! toggle || ! nav ) return;

	toggle.addEventListener( 'click', () => {
		const isOpen = nav.classList.toggle( 'is-open' );
		toggle.setAttribute( 'aria-expanded', isOpen );
	} );

	document.addEventListener( 'click', ( e ) => {
		if ( nav.classList.contains( 'is-open' ) && ! nav.contains( e.target ) && ! toggle.contains( e.target ) ) {
			nav.classList.remove( 'is-open' );
			toggle.setAttribute( 'aria-expanded', 'false' );
		}
	} );
}

// Appends a sliding underline indicator to `list` that follows hovered items
// and snaps back to `active` on mouse-out. Call once per group (main nav, WPML).
function createIndicator( list, items, active ) {
	if ( ! items.length ) return;

	const indicator = document.createElement( 'span' );
	indicator.className = 'site-nav__indicator';
	list.appendChild( indicator );

	const moveTo = ( el, duration = 0.25 ) => gsap.to( indicator, {
		x: el.offsetLeft, width: el.offsetWidth, duration, ease: 'power2.out',
	} );

	if ( active ) {
		gsap.set( indicator, { x: active.offsetLeft, width: active.offsetWidth, opacity: 1 } );
	} else {
		gsap.set( indicator, { opacity: 0 } );
	}

	items.forEach( el => el.addEventListener( 'mouseenter', () => {
		gsap.set( indicator, { opacity: 1 } );
		moveTo( el );
	} ) );

	list.addEventListener( 'mouseleave', () => {
		if ( active ) moveTo( active );
		else gsap.to( indicator, { opacity: 0, duration: 0.2 } );
	} );

	window.addEventListener( 'resize', () => {
		if ( active ) gsap.set( indicator, { x: active.offsetLeft, width: active.offsetWidth } );
	} );
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
