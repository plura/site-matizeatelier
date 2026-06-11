// Matize — dev tools
// Imported statically at the top of main.js so this runs before any other
// module body. Sets window.mtzDev synchronously; all modules can read it.
//
// Usage: append ?dev=<action>[,<action>] to any URL.
//
//   ?dev=no-intro    skip home scroll sections (statement + mood gallery)
//   ?dev=seed-form   fill the contact form with fixture data and open the modal

const params = new URLSearchParams( location.search );
const devParam = params.get( 'dev' );

if ( devParam ) {
	const actions = devParam.toLowerCase().replace( /-/g, '' ).split( ',' );

	window.mtzDev = {
		noIntro:  actions.includes( 'nointro' ),
		seedForm: actions.includes( 'seedform' ),
	};

	const log = ( msg ) => console.info( '%c[mtz:dev]%c ' + msg, 'color:#DA8300;font-weight:bold', '' );

	log( 'dev mode — ' + JSON.stringify( window.mtzDev ) );

	if ( window.mtzDev.noIntro ) {
		log( 'no-intro: home scroll sections (statement + mood) will be skipped' );
	}

	if ( window.mtzDev.seedForm ) {
		log( 'seed-form: filling contact form with fixture data' );
		import( './dev-seed.js' ).then( ( { mtzSeedContactForm } ) => mtzSeedContactForm() );
	}
}
