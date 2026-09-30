export function mtzInitGallery() {
	Fancybox.bind( '[data-fancybox]' );

	const bar = document.querySelector( '.gallery__filters' );
	if ( ! bar ) return;

	const filters = bar.querySelectorAll( '.gallery__filter' );
	const items = document.querySelectorAll( '.gallery__item[data-gallery-color]' );
	const status = document.querySelector( '[data-gallery-status]' );

	filters.forEach( ( filter ) => {
		filter.addEventListener( 'click', () => {
			const color = filter.dataset.galleryFilter;
			let shown = 0;
			filters.forEach( ( button ) => {
				const selected = button === filter;
				button.classList.toggle( 'is-active', selected );
				button.setAttribute( 'aria-pressed', String( selected ) );
			} );
			items.forEach( ( item ) => {
				item.hidden = color !== 'all' && item.dataset.galleryColor !== color;
				shown += item.hidden ? 0 : 1;
				// Fancybox groups every [data-fancybox] link at click time, hidden or not.
				const link = item.querySelector( 'a' );
				if ( item.hidden ) link.removeAttribute( 'data-fancybox' );
				else link.setAttribute( 'data-fancybox', 'gallery' );
			} );
			if ( status ) {
				status.textContent = ( shown === 1 ? bar.dataset.countOne : bar.dataset.countOther ).replace( '%d', shown );
			}
		} );
	} );
}
