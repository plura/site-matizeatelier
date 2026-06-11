// Matize — dev tools
// Loaded by main.js when ?dev= is present in the URL.
// window.mtzDev is set synchronously by main.js before this module runs,
// so all other modules can check it reliably.

const dev = window.mtzDev ?? {};
const log = ( msg ) => console.info( '%c[mtz:dev]%c ' + msg, 'color:#DA8300;font-weight:bold', '' );

log( 'dev mode active — ' + JSON.stringify( dev ) );

if ( dev.noIntro ) {
	log( 'no-intro: home scroll sections (statement + mood) will be skipped' );
}
