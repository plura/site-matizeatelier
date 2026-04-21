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
