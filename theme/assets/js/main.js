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

	// ── Contact modal ───────────────────────────────────────────────────────
	const modal     = document.querySelector( '#contact-modal' );
	const ctaBtn    = document.querySelector( '.cta__btn' );
	const closeBtn  = document.querySelector( '.contact-modal__close' );

	if ( modal && ctaBtn ) {
		ctaBtn.addEventListener( 'click', () => modal.showModal() );
	}

	if ( modal && closeBtn ) {
		closeBtn.addEventListener( 'click', () => modal.close() );
	}

	if ( modal ) {
		// Close on backdrop click
		modal.addEventListener( 'click', ( e ) => {
			if ( e.target === modal ) modal.close();
		} );
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
