document.addEventListener( 'DOMContentLoaded', () => {

	const paths = document.querySelectorAll( '.hero__logo svg path' );
	const video = document.querySelector( '.hero__video' );

	if ( !paths.length ) return;

	// Set each path's dasharray/offset to its actual length
	paths.forEach( path => {
		const length = path.getTotalLength();
		path.style.setProperty( '--path-length', length );
		path.style.strokeDasharray  = length;
		path.style.strokeDashoffset = length;
	} );

	// Draw logo in, then reveal video
	gsap.to( paths, {
		strokeDashoffset: 0,
		duration:         2,
		ease:             'power2.inOut',
		stagger:          0.1,
		delay:            0.3,
		onComplete() {
			if ( video ) gsap.to( video, { opacity: 1, duration: 1.5, ease: 'power1.inOut' } );
		},
	} );

} );
