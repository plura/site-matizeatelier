// Matize — main JS entry point

if ( new URLSearchParams( location.search ).has( 'test' ) ) {
	import( './test.js' );
}

import { mtzInitNav }                            from './nav.js';
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
mtzAnimPageTitle();

// ── Gallery ───────────────────────────────────────────────────────────────────
mtzAnimGalleryItems( document.querySelectorAll( '.gallery__item' ) );
if ( document.querySelector( '.gallery' ) ) {
	import( './gallery.js' ).then( ( { mtzInitGallery } ) => mtzInitGallery() );
}

// ── Scroll animations ─────────────────────────────────────────────────────────
if ( document.querySelector( '.section-header__title, .content-section--split' ) ) {
	import( './animations-scroll.js' ).then( ( { mtzAnimSectionHeaders, mtzAnimContentSections } ) => {
		mtzAnimSectionHeaders();
		mtzAnimContentSections();
	} );
}

// ── Home ──────────────────────────────────────────────────────────────────────
if ( document.querySelector( '.page-home' ) ) {
	import( './home.js' ).then( ( { mtzInitHome } ) => mtzInitHome() );
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

// ── Nav (mobile toggle + sliding underline) ───────────────────────────────────
mtzInitNav();
