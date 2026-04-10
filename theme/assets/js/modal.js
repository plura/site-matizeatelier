// Matize — generic dialog modal initialiser

export function mtzInitModal( modal, ctaBtn, closeBtn ) {

	if ( ! modal ) return;

	if ( ctaBtn ) {
		ctaBtn.addEventListener( 'click', () => modal.showModal() );
	}

	if ( closeBtn ) {
		closeBtn.addEventListener( 'click', () => modal.close() );
	}

	// Close on backdrop click
	modal.addEventListener( 'click', ( e ) => {
		if ( e.target === modal ) modal.close();
	} );

}
