// Matize — hero: wires up the scroll button, resets the video, and deploys
// the intro animation (unless skipped). type="module" (see manifest.json)
// runs this after the document is parsed, same timing as DOMContentLoaded —
// no listener needed.
import { mtzHeroIntroAnim } from './anim.js';

gsap.registerPlugin( ScrollToPlugin );

const hero        = document.querySelector( '.plura-wp-component .hero' );
const lettersPath = hero?.querySelector( '#mtz-logo-matize-letters' );
const dot         = hero?.querySelector( '#mtz-logo-matize-dot' );
const atelier     = hero?.querySelector( '#mtz-logo-atelier' );
const video       = hero?.querySelector( '.hero__video' );
const scrollBtn   = hero?.querySelector( '.hero__scroll' );

if ( hero && lettersPath ) {
	if ( scrollBtn ) {
		scrollBtn.addEventListener( 'click', ( e ) => {
			const target = hero.closest( '.plura-wp-component' )?.nextElementSibling;
			if ( target ) { e.preventDefault(); gsap.to( window, { scrollTo: target, duration: 1.2, ease: 'power2.inOut' } ); }
		} );
	}

	if ( video ) { video.pause(); video.currentTime = 0; }

	if ( window.mtzDev?.noIntro ) {
		if ( video ) video.play();
		hero.classList.add( 'is-intro-done' );
	} else {
		mtzHeroIntroAnim( { hero, lettersPath, dot, atelier, video } );
	}
}
