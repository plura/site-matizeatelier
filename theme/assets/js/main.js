// Matize — main JS entry point

document.addEventListener( 'DOMContentLoaded', () => {

	// ── Lucide icons ────────────────────────────────────────────────────────
	if ( typeof lucide !== 'undefined' ) {
		lucide.createIcons();
	}

	// ── Header height CSS variable ──────────────────────────────────────────
	const header = document.querySelector( '.site-header' );
	if ( header ) {
		const setHeaderHeight = () =>
			document.documentElement.style.setProperty( '--mtz-header-height', header.offsetHeight + 'px' );
		setHeaderHeight();
		window.addEventListener( 'resize', setHeaderHeight );
	}

	// ── Mobile nav toggle ───────────────────────────────────────────────────
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

} );
