// Matize — scroll-triggered reveal animations

gsap.registerPlugin( ScrollTrigger );

export function mtzAnimContentSections() {
	const sections = document.querySelectorAll( '.content-section--split' );
	if ( ! sections.length ) return;

	// Horizontal slide only makes sense in a two-column layout
	const isDesktop = window.innerWidth >= 768;

	sections.forEach( section => {
		const title       = section.querySelector( '.section-header__title' );
		const body        = section.querySelector( '.section-body' );
		const media       = section.querySelector( '.content-section__media' );
		const clusterImgs = media?.querySelectorAll( '.gallery-cluster__img' );
		const hasCluster  = !! clusterImgs?.length;

		// Shared trigger — all animations in a section share the same start point
		const trigger = {
			trigger:       section,
			start:         'top 78%',
			toggleActions: 'play none none none',
		};

		// Title slides up and fades in first
		if ( title ) {
			gsap.from( title, {
				autoAlpha: 0,
				y:         30,
				duration:  0.7,
				ease:      'power2.out',
				scrollTrigger: trigger,
			} );
		}

		// Body follows with a short delay so it sequences after the title
		if ( body ) {
			gsap.from( body, {
				autoAlpha: 0,
				y:         20,
				duration:  0.7,
				delay:     0.15,
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
