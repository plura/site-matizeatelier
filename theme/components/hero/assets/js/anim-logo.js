// Matize — hero logo intro animation: the script "matize" is written out left
// to right like a pen, then the i-dot pops and the wordmark rises.
// Scoped to the #mtz-logo SVG itself and nothing past it — returns the
// timeline (or null if the expected logo parts aren't found) and leaves
// what happens on completion to the caller.
//
// The letters are a filled outline, not a pen centreline, so the "pen" is the
// outline traced from its leftmost point (the start of the "m") along both
// edges at once, towards the tail. Sharp turns in the outline become beats:
// each stroke between them speeds up and slows down, with a short hold at the
// turn — the rhythm of handwriting rather than one even sweep.

const NS = 'http://www.w3.org/2000/svg';

const DRAW_TIME = 2.4;          // seconds of pen movement, holds excluded
const MAX_BEATS = 9;            // pauses along the word
const SEED      = 7;            // fixed: the "hand" writes the same way every visit

export function mtzHeroIntroAnim( logo ) {
	const letters = logo?.querySelector( '#mtz-logo-matize-letters' );
	const dot     = logo?.querySelector( '#mtz-logo-matize-dot' );
	const atelier = logo?.querySelector( '#mtz-logo-atelier' );

	if ( !letters ) return null;

	const rand = mulberry32( SEED );
	const jitter = ( min, max ) => min + rand() * ( max - min );

	// ── Pen strokes: stroke-only copies of the outline's subpaths ──
	// The filled path keeps all subpaths so the "e"/"z" counters stay holes.
	const [ mainD, ...counterDs ] = mtzSubpaths( letters ).sort( ( a, b ) => b.length - a.length ).map( ( s ) => s.d );
	const fwd      = mtzStrokePath( letters, mainD );
	const back     = mtzStrokePath( letters, mainD );
	const counters = counterDs.map( ( d ) => mtzStrokePath( letters, d ) );
	const strokes  = [ fwd, back, ...counters ];

	const L   = fwd.getTotalLength();
	const pts = mtzSample( fwd, L, 4 );
	const split = pts.reduce( ( min, p ) => ( p.x < min.x ? p : min ) ).s; // leftmost point
	const span  = L - split;

	// ── Beats: sharpest turns along the forward edge, spaced apart ──
	const ahead  = pts.filter( ( p ) => p.s >= split );
	const w      = 6;
	const turns  = ahead.map( ( p, i ) => {
		if ( i < w || i >= ahead.length - w ) return 0;
		const a1 = Math.atan2( p.y - ahead[ i - w ].y, p.x - ahead[ i - w ].x );
		const a2 = Math.atan2( ahead[ i + w ].y - p.y, ahead[ i + w ].x - p.x );
		return Math.abs( Math.atan2( Math.sin( a2 - a1 ), Math.cos( a2 - a1 ) ) );
	} );
	const minGap = ahead.length / ( MAX_BEATS + 3 );
	const picked = [];
	turns
		.map( ( t, i ) => ( { t, i } ) )
		.filter( ( c ) => c.t > 1 )
		.sort( ( a, b ) => b.t - a.t )
		.forEach( ( c ) => {
			if ( picked.length < MAX_BEATS && picked.every( ( i ) => Math.abs( i - c.i ) > minGap ) ) picked.push( c.i );
		} );
	const beats = [ 0, ...picked.sort( ( a, b ) => a - b ).map( ( i ) => ( ahead[ i ].s - split ) / span ), 1 ];

	// ── Initial states ──
	const pen = { u: 0 };
	const renderPen = () => {
		mtzShowRange( fwd, split, split + pen.u * span, L );
		mtzShowRange( back, split - pen.u * split, split, L );
	};
	renderPen();
	counters.forEach( ( c ) => mtzShowRange( c, 0, 0, c.getTotalLength() ) );
	gsap.set( dot,     { scale: 0, transformOrigin: 'center center' } );
	gsap.set( atelier, { opacity: 0, y: 10 } );

	const tl = gsap.timeline( { delay: 0.3 } );

	// 1. Write the word: one tween per stroke, holds at the beats. Short strokes
	//    get proportionally more time (length^0.8), as a hand slows for detail.
	const weights = beats.slice( 1 ).map( ( u, i ) => ( u - beats[ i ] ) ** 0.8 * jitter( 0.85, 1.15 ) );
	const total   = weights.reduce( ( a, b ) => a + b, 0 );
	const strokeTimes = []; // [start, end, uFrom, uTo] for placing the counters
	let t = 0;
	weights.forEach( ( wgt, i ) => {
		const duration = DRAW_TIME * wgt / total;
		tl.to( pen, { u: beats[ i + 1 ], duration, ease: 'power2.inOut', onUpdate: renderPen }, t );
		strokeTimes.push( [ t, t + duration, beats[ i ], beats[ i + 1 ] ] );
		t += duration + ( i < weights.length - 1 ? jitter( 0.05, 0.14 ) : 0 );
	} );

	// 1b. Counters (the "e" eye, the "z" loop): drawn when the pen reaches them
	counters.forEach( ( c ) => {
		const cl   = c.getTotalLength();
		const minX = Math.min( ...mtzSample( c, cl, 4 ).map( ( p ) => p.x ) );
		const hit  = ahead.find( ( p ) => p.x >= minX ) ?? ahead[ ahead.length - 1 ];
		const u    = ( hit.s - split ) / span;
		const [ s0, s1, u0, u1 ] = strokeTimes.find( ( [ , , a, b ] ) => u >= a && u <= b ) ?? strokeTimes[ strokeTimes.length - 1 ];
		const at   = s0 + ( s1 - s0 ) * ( ( u - u0 ) / ( u1 - u0 || 1 ) );
		const draw = { v: 0 };
		tl.to( draw, { v: 1, duration: 0.35, ease: 'power1.inOut', onUpdate: () => mtzShowRange( c, 0, draw.v * cl, cl ) }, at );
	} );

	// 1c. Cross-fade: fill in, pen strokes out
	tl.to( letters, { fillOpacity: 1, duration: 0.4, ease: 'power1.inOut' } );
	tl.to( strokes, { strokeOpacity: 0, duration: 0.4, ease: 'power1.inOut' }, '<' );

	// 2. Dot the i — after the word, as a hand would
	tl.to( dot, {
		opacity:  1,
		scale:    1,
		duration: 0.5,
		ease:     'back.out(2)',
	}, '+=0.3' );

	// 3. Atelier wordmark — simple slide up + fade in, as one unit
	tl.to( atelier, {
		opacity:  1,
		y:        0,
		duration: 0.5,
		ease:     'power2.out',
	}, '-=0.1' );

	// 4. Hold the complete logo for a beat before the timeline (and intro) ends
	tl.to( {}, { duration: 1 } );

	return tl;
}

/**
 * Splits a compound path into its subpaths, each rebased onto an absolute
 * start (a relative `m` is relative to the previous subpath's end point).
 *
 * @param {SVGPathElement} el
 * @returns {{d: string, length: number}[]}
 */
function mtzSubpaths( el ) {
	const probe = document.createElementNS( NS, 'path' );
	el.after( probe ); // measured in the document, so geometry is available
	let end = null;
	const out = el.getAttribute( 'd' ).trim().split( /(?=[Mm])/ ).map( ( part ) => {
		let d = part;
		const m = end && part.match( /^m\s*(-?[\d.]+)[\s,]*(-?[\d.]+)(.*)$/s );
		if ( m ) d = `M${ end.x + +m[ 1 ] },${ end.y + +m[ 2 ] }${ m[ 3 ] }`;
		probe.setAttribute( 'd', d );
		const length = probe.getTotalLength();
		end = probe.getPointAtLength( length );
		return { d, length };
	} );
	probe.remove();
	return out;
}

/**
 * Adds a stroke-only copy of a path right after the letters (styled by
 * .mtz-logo-stroke in style.css).
 *
 * @param {SVGPathElement} after
 * @param {string}         d
 * @returns {SVGPathElement}
 */
function mtzStrokePath( after, d ) {
	const p = document.createElementNS( NS, 'path' );
	p.setAttribute( 'd', d );
	p.setAttribute( 'class', 'mtz-logo-stroke' );
	after.after( p );
	return p;
}

/**
 * Samples points along a path every `step` units.
 *
 * @param {SVGPathElement} el
 * @param {number}         length
 * @param {number}         step
 * @returns {{s: number, x: number, y: number}[]}
 */
function mtzSample( el, length, step ) {
	const pts = [];
	for ( let s = 0; s <= length; s += step ) {
		const { x, y } = el.getPointAtLength( s );
		pts.push( { s, x, y } );
	}
	return pts;
}

/**
 * Shows only the [from, to] stretch of a path's stroke. The dash starts at
 * `from` via a negative offset; an empty stretch is a zero-length dash, which
 * renders nothing because .mtz-logo-stroke uses butt caps.
 *
 * @param {SVGPathElement} el
 * @param {number}         from
 * @param {number}         to
 * @param {number}         length  The path's total length.
 * @returns {void}
 */
function mtzShowRange( el, from, to, length ) {
	el.style.strokeDasharray  = `${ Math.max( 0, to - from ) } ${ length * 2 }`;
	el.style.strokeDashoffset = -from;
}

/**
 * Small seeded PRNG, so the stroke timing jitter is identical on every load.
 *
 * @param {number} seed
 * @returns {function(): number} Returns floats in [0, 1).
 */
function mulberry32( seed ) {
	return () => {
		seed = ( seed + 0x6D2B79F5 ) | 0;
		let t = Math.imul( seed ^ ( seed >>> 15 ), 1 | seed );
		t = ( t + Math.imul( t ^ ( t >>> 7 ), 61 | t ) ) ^ t;
		return ( ( t ^ ( t >>> 14 ) ) >>> 0 ) / 4294967296;
	};
}
