/**
 * Compiles every brevo/<issue>/*.mjml to a sibling .html, then fills the shared
 * partials' %PLACEHOLDERS% from values.json (WordPress fills them on the site;
 * Brevo has no such step). Fails if a placeholder is left unfilled.
 */
const fs = require( 'fs' );
const path = require( 'path' );
const { execSync } = require( 'child_process' );

const root = path.join( __dirname, '..' );
const values = { ...require( './values.json' ), YEAR: String( new Date().getFullYear() ) };

for ( const issue of fs.readdirSync( __dirname, { withFileTypes: true } ) ) {
	if ( ! issue.isDirectory() || issue.name.startsWith( '_' ) ) continue;

	for ( const file of fs.readdirSync( path.join( __dirname, issue.name ) ) ) {
		if ( ! file.endsWith( '.mjml' ) ) continue;

		const src = `brevo/${ issue.name }/${ file }`;
		const out = src.replace( /\.mjml$/, '.html' );
		console.log( src );
		execSync( `npx mjml ${ src } -o ${ out } --config.allowIncludes true --config.includePath . --config.minify true --config.keepComments false`, { cwd: root, stdio: 'inherit' } );

		const html = fs.readFileSync( path.join( root, out ), 'utf8' )
			.replace( /%([A-Z_]+)%/g, ( match, key ) => values[ key ] ?? match );
		const left = [ ...new Set( html.match( /%[A-Z_]+%/g ) ) ];
		if ( left.length ) throw new Error( `${ out }: no value for ${ left.join( ', ' ) }` );
		fs.writeFileSync( path.join( root, out ), html );
	}
}
