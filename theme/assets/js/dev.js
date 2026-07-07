// Matize — dev tools
// Imported statically at the top of main.js so this runs before any other
// module body. Sets window.mtzDev synchronously; all modules can read it.
//
// Usage: append ?dev=<action>[,<action>] to any URL.
//
//   ?dev=no-intro        skip home scroll sections (statement + mood gallery)
//   ?dev=seed-form       fill the contact form with fixture data and open the modal
//   ?dev=header-opacity  page-header title test: huge font-size, near-invisible
//                        opacity, bg-vectors hidden (see dev-tests.css)

const params = new URLSearchParams( location.search );
const devParam = params.get( 'dev' );

if ( devParam ) {
	const actions = devParam.toLowerCase().replace( /-/g, '' ).split( ',' );

	window.mtzDev = {
		noIntro:       actions.includes( 'nointro' ),
		seedForm:      actions.includes( 'seedform' ),
		headerOpacity: actions.includes( 'headeropacity' ),
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

	if ( window.mtzDev.headerOpacity ) {
		log( 'header-opacity: giant near-invisible title, bg-vectors hidden' );
		document.body.classList.add( 'dev-header-opacity' );

		const link = document.createElement( 'link' );
		link.rel  = 'stylesheet';
		link.href = new URL( '../css/dev-tests.css', import.meta.url ).href;
		document.head.appendChild( link );
	}
}
