// Matize — main JS entry point

// ── Dev / test flags — must run before any module setup ──────────────────────
const _params = new URLSearchParams( location.search );

if ( _params.has( 'test' ) ) {
	import( './test.js' );
}

{
	const _dev = _params.get( 'dev' );
	if ( _dev ) {
		const _actions = _dev.toLowerCase().replace( /-/g, '' ).split( ',' );
		window.mtzDev = { noIntro: _actions.includes( 'nointro' ) };
		import( './dev.js' );
	}
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
if ( document.querySelector( '.section-header__title, .content-section--split, .grid' ) ) {
	import( './animations-scroll.js' ).then( ( { mtzAnimSectionHeaders, mtzAnimContentSections, mtzAnimGridItems } ) => {
		mtzAnimSectionHeaders();
		mtzAnimContentSections();
		mtzAnimGridItems();
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
