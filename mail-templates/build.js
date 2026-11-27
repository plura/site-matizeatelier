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

const EMAIL_BASE = 'https://matizeatelier.pt/email/';

/**
 * Reads a JPEG's or PNG's pixel size from its header.
 *
 * @param {string} file Absolute path.
 * @return {{width: number, height: number}|null} Null for other formats.
 */
function imageSize( file ) {
	const b = fs.readFileSync( file );
	if ( b.readUInt32BE( 0 ) === 0x89504e47 ) return { width: b.readUInt32BE( 16 ), height: b.readUInt32BE( 20 ) };
	for ( let i = 2; b[ 0 ] === 0xff && i < b.length; i += 2 + b.readUInt16BE( i + 2 ) ) {
		if ( b[ i + 1 ] >= 0xc0 && b[ i + 1 ] <= 0xc2 ) return { width: b.readUInt16BE( i + 7 ), height: b.readUInt16BE( i + 5 ) };
	}
	return null;
}

/**
 * Replaces MJML's height="auto" on our hosted images with the real height, so
 * clients reserve the space before the image loads and the text doesn't jump.
 * Only the attribute changes: the inline height:auto still scales it on phones.
 *
 * @param {string} html Compiled HTML.
 * @return {string}
 */
function fixImageHeights( html ) {
	return html.replace( /<img\b[^>]*>/g, ( tag ) => {
		const src = tag.match( /src="([^"]+)"/ )?.[ 1 ] ?? '';
		const width = Number( tag.match( /\swidth="(\d+)"/ )?.[ 1 ] );
		if ( ! src.startsWith( EMAIL_BASE ) || ! width || ! tag.includes( 'height="auto"' ) ) return tag;

		const size = imageSize( path.join( __dirname, src.slice( EMAIL_BASE.length ) ) );
		return size ? tag.replace( 'height="auto"', `height="${ Math.round( width * size.height / size.width ) }"` ) : tag;
	} );
}

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
	const file = path.join( __dirname, out );
	fs.writeFileSync( file, fixImageHeights( fs.readFileSync( file, 'utf8' ) ) );
	return file;
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
