/**
 * Compiles the email templates. `node build.js site|brevo`, or no argument for both.
 * - site: contact and generic templates, copied to the theme and plugin, where
 *   WordPress fills their %PLACEHOLDERS% when sending.
 * - brevo: every brevo/<issue>/*.mjml; the placeholders are filled here from
 *   brevo/values.json, since Brevo has no such step. Fails if one is left.
 */
const fs = require( 'fs' );
const path = require( 'path' );
const { execSync } = require( 'child_process' );

const mjmlFiles = ( dir ) => fs.readdirSync( path.join( __dirname, dir ) )
	.filter( ( f ) => f.endsWith( '.mjml' ) )
	.map( ( f ) => `${ dir }/${ f }` );

/**
 * Compiles one template to a sibling .html.
 *
 * @param {string} src Path relative to mail-templates/.
 * @return {string} Absolute path of the compiled HTML.
 */
function compile( src ) {
	const out = src.replace( /\.mjml$/, '.html' );
	console.log( src );
	execSync( `npx mjml ${ src } -o ${ out } --config.allowIncludes true --config.includePath . --config.minify true --config.keepComments false`, { cwd: __dirname, stdio: 'inherit' } );
	return path.join( __dirname, out );
}

const targets = {
	site() {
		const copies = [
			...mjmlFiles( 'contact' ).map( ( src ) => [ src, '../theme/templates/contact' ] ),
			...mjmlFiles( 'generic' ).map( ( src ) => [ src, '../plugin/templates' ] ),
		];
		for ( const [ src, dest ] of copies ) {
			const out = compile( src );
			fs.copyFileSync( out, path.join( __dirname, dest, path.basename( out ) ) );
		}
	},

	brevo() {
		const values = { ...require( './brevo/values.json' ), YEAR: String( new Date().getFullYear() ) };
		const issues = fs.readdirSync( path.join( __dirname, 'brevo' ), { withFileTypes: true } )
			.filter( ( d ) => d.isDirectory() && ! d.name.startsWith( '_' ) );

		for ( const issue of issues ) {
			for ( const src of mjmlFiles( `brevo/${ issue.name }` ) ) {
				const out = compile( src );
				const html = fs.readFileSync( out, 'utf8' ).replace( /%([A-Z_]+)%/g, ( match, key ) => values[ key ] ?? match );
				const left = [ ...new Set( html.match( /%[A-Z_]+%/g ) ) ];
				if ( left.length ) throw new Error( `${ src }: no value for ${ left.join( ', ' ) }` );
				fs.writeFileSync( out, html );
			}
		}
	},
};

const only = process.argv[ 2 ];
if ( only && ! targets[ only ] ) throw new Error( `Unknown target "${ only }". Use: ${ Object.keys( targets ).join( ', ' ) }` );
for ( const [ name, run ] of Object.entries( targets ) ) if ( ! only || only === name ) run();
