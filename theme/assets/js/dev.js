// Matize — dev / test tools
// Imported statically at the top of main.js so this runs before any other
// module body. Sets window.mtzDev synchronously; all modules can read it.

const params = new URLSearchParams( location.search );

// ── test ──────────────────────────────────────────────────────────────────────
if ( params.has( 'test' ) ) {
	import( './test.js' );
}

// ── dev ───────────────────────────────────────────────────────────────────────
const devParam = params.get( 'dev' );

if ( devParam ) {
	const actions = devParam.toLowerCase().replace( /-/g, '' ).split( ',' );

	window.mtzDev = {
		noIntro: actions.includes( 'nointro' ),
	};

	const log = ( msg ) => console.info( '%c[mtz:dev]%c ' + msg, 'color:#DA8300;font-weight:bold', '' );

	log( 'dev mode — ' + JSON.stringify( window.mtzDev ) );

	if ( window.mtzDev.noIntro ) {
		log( 'no-intro: home scroll sections (statement + mood) will be skipped' );
	}
}
