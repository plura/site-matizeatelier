// Matize — shared JS utilities

// Injects a <link rel="stylesheet">, resolved relative to the calling
// module's own URL — pass import.meta.url so the path resolves correctly
// regardless of where that module is loaded from.
export function mtzLoadStylesheet( relativePath, moduleUrl ) {
	const link = document.createElement( 'link' );
	link.rel  = 'stylesheet';
	link.href = new URL( relativePath, moduleUrl ).href;
	document.head.appendChild( link );
	return link;
}
