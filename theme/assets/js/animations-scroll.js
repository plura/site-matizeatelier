// Matize — scroll-triggered reveal animations

gsap.registerPlugin( ScrollTrigger );

// ── Section header reveals ────────────────────────────────────────────────────
// All .section-header__title and .section-header__title-intro elements on the page, regardless
// of whether they live inside a split section or a standalone block (e.g. Brands).
export function mtzAnimSectionHeaders() {
	ScrollTrigger.batch( '.section-header__title', {
		start: 'top 85%',
		onEnter: batch => gsap.from( batch, {
			autoAlpha: 0,
			y:         24,
			stagger:   0.08,
			duration:  0.7,
			ease:      'power2.out',
		} ),
	} );

	ScrollTrigger.batch( '.section-header__title-intro', {
		start: 'top 85%',
		onEnter: batch => gsap.from( batch, {
			autoAlpha: 0,
			y:         16,
			duration:  0.6,
			ease:      'power2.out',
		} ),
	} );
}

// ── Grid items reveal ─────────────────────────────────────────────────────────
// Any element with .grid — children stagger in on scroll.
export function mtzAnimGridItems() {
	ScrollTrigger.batch( '.grid > *', {
		start: 'top 90%',
		onEnter: batch => gsap.from( batch, {
			autoAlpha: 0,
			y:         20,
			stagger:   0.08,
			duration:  0.5,
			ease:      'power2.out',
		} ),
	} );
}

export function mtzAnimContentSections() {
	const sections = document.querySelectorAll( '.content-section--split' );
	if ( ! sections.length ) return;

	// Horizontal slide only makes sense in a two-column layout
	const isDesktop = window.innerWidth >= 768;

	sections.forEach( section => {
		const body        = section.querySelector( '.section-body' );
		const media       = section.querySelector( '.content-section__media' );
		const clusterImgs = media?.querySelectorAll( '.gallery-cluster__img' );
		const hasCluster  = !! clusterImgs?.length;

		// Shared trigger — media animations use the same start point
		const trigger = {
			trigger:       section,
			start:         'top 78%',
			toggleActions: 'play none none none',
		};

		// Body fades in (title is handled by the global section-header batch above)
		if ( body ) {
			gsap.from( body, {
				autoAlpha: 0,
				y:         20,
				duration:  0.7,
				ease:      'power2.out',
				scrollTrigger: trigger,
			} );
		}

		if ( media ) {
			// Single images (service sections) slide in from their visual edge.
			// Cluster media just fades — the cluster images animate independently below.
			let xFrom = 0;
			if ( isDesktop && ! hasCluster ) {
				const bodyEl   = section.querySelector( '.content-section__body' );
				const fromLeft = bodyEl
					? media.getBoundingClientRect().left < bodyEl.getBoundingClientRect().left
					: true;
				xFrom = fromLeft ? -50 : 50;
			}

			gsap.from( media, {
				autoAlpha: 0,
				x:         xFrom,
				y:         isDesktop && ! hasCluster ? 0 : 20,
				duration:  0.8,
				ease:      'power2.out',
				scrollTrigger: { ...trigger, start: 'top 82%' },
			} );

			// Cluster images stagger in individually after the container fades in
			if ( hasCluster ) {
				gsap.from( clusterImgs, {
					autoAlpha: 0,
					y:         20,
					stagger:   0.1,
					duration:  0.6,
					delay:     0.2,
					ease:      'power2.out',
					scrollTrigger: { ...trigger, start: 'top 82%' },
				} );
			}
		}

	} );
}

// ── Image stack — collapsed to spread ──────────────────────────────────────────
// Each .img-stack's ghosts/cards already have their fanned "spread" position and
// rotation baked into CSS (transform: rotate() translateY(), etc). Rather than
// duplicating those values in JS, read each layer's current (already-final)
// bounding box, and gsap.from() a centred/unrotated starting point back to it —
// this animates correctly however the CSS positions are tuned, with no JS changes.
export function mtzAnimImgStacks() {
	const stacks = document.querySelectorAll( '.img-stack' );

	stacks.forEach( stack => {
		const layers = [ ...stack.querySelectorAll( '.img-ghost, .img-card' ) ];
		if ( ! layers.length ) return;

		const stackRect = stack.getBoundingClientRect();
		const centerX   = stackRect.left + stackRect.width / 2;
		const centerY   = stackRect.top + stackRect.height / 2;

		const tl = gsap.timeline();

		layers.forEach( ( layer, i ) => {
			const rect = layer.getBoundingClientRect();

			tl.from( layer, {
				x:        centerX - ( rect.left + rect.width / 2 ),
				y:        centerY - ( rect.top + rect.height / 2 ),
				rotation: 0,
				duration: 1,
			}, i * 0.15 );
		} );

		ScrollTrigger.create( {
			trigger:   stack,
			start:     'top 85%',
			end:       'top 35%',
			scrub:     1,
			animation: tl,
		} );
	} );
}

// ── Background vector parallax ───────────────────────────────────────────────
// Decorative furniture line-art drifts at a different rate than the page's own
// scroll — reinforces that these sit behind the content (z-index: -1) rather
// than competing with it for a reveal moment. Scrubbed to each vector's own
// scroll-through of the viewport, not a one-shot reveal.
export function mtzAnimBgVectors() {
	const vectors = document.querySelectorAll( '.bg-vector' );

	vectors.forEach( vector => {
		gsap.to( vector, {
			// TODO: diagnostic magnitude — confirm the effect is visible/firing,
			// then dial back down (was -60, nearly imperceptible over this range).
			y:    -250,
			ease: 'none',
			scrollTrigger: {
				trigger: vector,
				start:   'top bottom',
				end:     'bottom top',
				scrub:   true,
			},
		} );
	} );
}
