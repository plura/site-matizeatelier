// Matize — main JS entry point

import { mtzInitModal }                         from './modal.js';
import { mtzAnimPageTitle, mtzAnimGalleryItems } from './animations.js';

// ── Lucide icons ─────────────────────────────────────────────────────────────
if ( typeof lucide !== 'undefined' ) {
	lucide.createIcons();
}

// ── Header height CSS variable ────────────────────────────────────────────────
const header = document.querySelector( '.site-header' );
if ( header ) {
	const setHeaderHeight = () =>
		document.documentElement.style.setProperty( '--mtz-header-height', header.offsetHeight + 'px' );
	setHeaderHeight();
	window.addEventListener( 'resize', setHeaderHeight );
}

// ── Page title animation ──────────────────────────────────────────────────────
mtzAnimPageTitle( document.querySelector( '.page-header__title' ) );

// ── Gallery ───────────────────────────────────────────────────────────────────
mtzAnimGalleryItems( document.querySelectorAll( '.gallery__item' ) );
if ( document.querySelector( '.gallery' ) ) {
	import( './gallery.js' ).then( ( { mtzInitGallery } ) => mtzInitGallery() );
}

// ── Home ──────────────────────────────────────────────────────────────────────
if ( document.querySelector( '.page-home' ) ) {
	import( './home.js' ).then( ( { mtzInitStatements, mtzInitMood } ) => {
		mtzInitStatements();
		mtzInitMood();
	} );
}

// ── Contact modal ─────────────────────────────────────────────────────────────
mtzInitModal( {
	modal:    document.querySelector( '#contact-modal' ),
	trigger:  document.querySelector( '.cta__btn' ),
	closeBtn: document.querySelector( '.contact-modal__close' ),
} );

// ── Header scroll state — toggle .is-scrolled on any page ────────────────────
if ( header ) {
	const onScroll = () => header.classList.toggle( 'is-scrolled', window.scrollY > 0 );
	window.addEventListener( 'scroll', onScroll, { passive: true } );
	onScroll(); // sync on load in case page is already scrolled
}

// ── Mobile nav toggle ─────────────────────────────────────────────────────────
const toggle = document.querySelector( '.site-header__menu-toggle' );
const nav    = document.querySelector( '#site-nav' );

if ( toggle && nav ) {
	toggle.addEventListener( 'click', () => {
		const isOpen = nav.classList.toggle( 'is-open' );
		toggle.setAttribute( 'aria-expanded', isOpen );
		document.body.classList.toggle( 'nav-open', isOpen );
	} );

	// Close on outside click
	document.addEventListener( 'click', ( e ) => {
		if ( nav.classList.contains( 'is-open' ) && ! nav.contains( e.target ) && ! toggle.contains( e.target ) ) {
			nav.classList.remove( 'is-open' );
			toggle.setAttribute( 'aria-expanded', 'false' );
			document.body.classList.remove( 'nav-open' );
		}
	} );
}
