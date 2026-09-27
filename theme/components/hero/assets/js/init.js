// Matize — hero: wires up the scroll button, resets the video, and deploys
// the intro animation (unless skipped, or already seen this session).
// type="module" (see manifest.json) runs this after the document is
// parsed, same timing as DOMContentLoaded — no listener needed.
import { mtzHeroIntroAnim } from './anim-logo.js';

gsap.registerPlugin( ScrollToPlugin );

const INTRO_SEEN_KEY = 'mtz:hero-intro-seen';

const hero          = document.querySelector( '.plura-wp-component .hero' );
const logo          = hero?.querySelector( '#mtz-logo' );
const video         = hero?.querySelector( '.hero__video' );
const scrollBtn     = hero?.querySelector( '.hero__scroll' );
const reducedMotion = matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

if ( hero ) {
	if ( scrollBtn ) {
		scrollBtn.addEventListener( 'click', ( e ) => {
			const target = hero.closest( '.plura-wp-component' )?.nextElementSibling;
			if ( target ) { e.preventDefault(); gsap.to( window, { scrollTo: target, duration: reducedMotion ? 0 : 1.2, ease: 'power2.inOut' } ); }
		} );
	}

	if ( video ) { video.pause(); video.currentTime = 0; }

	const revealHero = () => {
		hero.classList.add( 'is-intro-done' );
		// The video is display:none under reduced motion; play() can also be
		// refused (e.g. iOS low-power mode) — don't leave an unhandled rejection.
		if ( video && !reducedMotion ) video.play().catch( () => {} );
	};

	// sessionStorage can throw under some privacy settings — never let that
	// take the scroll button / video reset above down with it.
	let introSeen = false;
	try { introSeen = sessionStorage.getItem( INTRO_SEEN_KEY ) === '1'; } catch {}

	// Reduced motion: no draw-on intro, and no page lock while it plays.
	const skipIntro = window.mtzDev?.noIntro || introSeen || reducedMotion;

	// Build the timeline either way: the logo's own parts start CSS-hidden
	// (FOUC guard in style.css) and only GSAP's tweens ever reveal them, so a
	// skipped intro still needs to jump straight to that finished state
	// rather than leaving the logo invisible.
	const tl = mtzHeroIntroAnim( logo );

	if ( tl && !skipIntro ) {
		try { sessionStorage.setItem( INTRO_SEEN_KEY, '1' ); } catch {}
		tl.eventCallback( 'onComplete', revealHero );
	} else {
		tl?.progress( 1 );
		revealHero();
	}
}
