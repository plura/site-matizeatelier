// Matize — hero: wires up the scroll button, resets the video, and deploys
// the intro animation (unless skipped, or already seen this session).
// type="module" (see manifest.json) runs this after the document is
// parsed, same timing as DOMContentLoaded — no listener needed.
import { mtzHeroIntroAnim } from './anim-logo.js';

gsap.registerPlugin( ScrollToPlugin );

const INTRO_SEEN_KEY = 'mtz:hero-intro-seen';

const hero      = document.querySelector( '.plura-wp-component .hero' );
const logo      = hero?.querySelector( '#mtz-logo' );
const video     = hero?.querySelector( '.hero__video' );
const scrollBtn = hero?.querySelector( '.hero__scroll' );

if ( hero ) {
	if ( scrollBtn ) {
		scrollBtn.addEventListener( 'click', ( e ) => {
			const target = hero.closest( '.plura-wp-component' )?.nextElementSibling;
			if ( target ) { e.preventDefault(); gsap.to( window, { scrollTo: target, duration: 1.2, ease: 'power2.inOut' } ); }
		} );
	}

	if ( video ) { video.pause(); video.currentTime = 0; }

	const revealHero = () => {
		hero.classList.add( 'is-intro-done' );
		if ( video ) video.play();
	};

	// sessionStorage can throw under some privacy settings — never let that
	// take the scroll button / video reset above down with it.
	let introSeen = false;
	try { introSeen = sessionStorage.getItem( INTRO_SEEN_KEY ) === '1'; } catch {}

	if ( window.mtzDev?.noIntro || introSeen ) {
		revealHero();
	} else {
		try { sessionStorage.setItem( INTRO_SEEN_KEY, '1' ); } catch {}
		const tl = mtzHeroIntroAnim( logo );
		if ( tl ) tl.eventCallback( 'onComplete', revealHero );
		else revealHero();
	}
}
