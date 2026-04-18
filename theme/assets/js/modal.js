// Matize — generic dialog modal initialiser

const FOCUSABLE = 'a[href], button:not([disabled]), input:not([disabled]), textarea:not([disabled]), select:not([disabled]), [tabindex]:not([tabindex="-1"])';

export function mtzInitModal( { modal, trigger, closeBtn } ) {

	if ( ! modal ) return;

	let opener = null;

	const open = () => {
		opener = document.activeElement;
		modal.showModal();
		trapFocus();
	};

	const close = () => {
		modal.close();
		opener?.focus();
	};

	const trapFocus = () => {
		const els = [ ...modal.querySelectorAll( FOCUSABLE ) ];
		if ( ! els.length ) return;
		const first = els[ 0 ];
		const last  = els[ els.length - 1 ];

		const onKeyDown = ( e ) => {
			if ( e.key !== 'Tab' ) return;
			if ( e.shiftKey ) {
				if ( document.activeElement === first ) { e.preventDefault(); last.focus(); }
			} else {
				if ( document.activeElement === last )  { e.preventDefault(); first.focus(); }
			}
		};

		modal.addEventListener( 'keydown', onKeyDown );
		modal.addEventListener( 'close', () => modal.removeEventListener( 'keydown', onKeyDown ), { once: true } );
	};

	if ( trigger ) trigger.addEventListener( 'click', open );
	if ( closeBtn ) closeBtn.addEventListener( 'click', close );

	// Close on backdrop click
	modal.addEventListener( 'click', ( e ) => {
		if ( e.target === modal ) close();
	} );

}
