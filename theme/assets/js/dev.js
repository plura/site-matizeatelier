// Matize — dev tools
// Imported statically at the top of main.js so this runs before any other
// module body. Sets window.mtzDev synchronously; all modules can read it.
//
// Two independent query params:
//   ?dev=<action>[,<action>]        stable dev utilities (permanent)
//   ?dev-test=<test>[,<test>]       disposable client-review design tests
//                                   (numbered to match client feedback — see
//                                   dev-tests.css, deleted once a test wins)
//
//   ?dev=no-intro    skip home scroll sections (statement + mood gallery)
//   ?dev=seed-form   fill the contact form with fixture data and open the modal
//   ?dev-test=test1  page-header title test: huge font-size, near-invisible
//                    opacity, bg-vectors hidden, no hyphenation

import { mtzLoadStylesheet } from './utils.js';

const params      = new URLSearchParams( location.search );
const devParam    = params.get( 'dev' );
const devTestParam = params.get( 'dev-test' );

const log = ( msg ) => console.info( '%c[mtz:dev]%c ' + msg, 'color:#DA8300;font-weight:bold', '' );

if ( devParam ) {
	const actions = devParam.toLowerCase().replace( /-/g, '' ).split( ',' );

	window.mtzDev = {
		noIntro:  actions.includes( 'nointro' ),
		seedForm: actions.includes( 'seedform' ),
	};

	log( 'dev mode — ' + JSON.stringify( window.mtzDev ) );

	if ( window.mtzDev.noIntro ) {
		log( 'no-intro: home scroll sections (statement + mood) will be skipped' );
	}

	if ( window.mtzDev.seedForm ) {
		log( 'seed-form: filling contact form with fixture data' );
		import( './dev-seed.js' ).then( ( { mtzSeedContactForm } ) => mtzSeedContactForm() );
	}
}

if ( devTestParam ) {
	const tests = devTestParam.toLowerCase().split( ',' );

	log( 'dev-test — ' + JSON.stringify( tests ) );

	tests.forEach( ( test ) => document.body.classList.add( 'dev-' + test ) );

	mtzLoadStylesheet( '../css/dev-tests.css', import.meta.url );
}
