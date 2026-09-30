<?php
/**
 * Gallery color filter bar. Skipped unless at least two colors are present, since a single
 * option filters nothing. Unclassified images ('' color) appear under "All colors" only.
 *
 * @param array $args {
 *     @type string[] $colors Color slug of each gallery item.
 * }
 */
$labels = mtz_image_color_labels();
$colors = array_intersect( array_keys( $labels ), $args['colors'] ?? [] );
if ( count( $colors ) < 2 ) return;
?>

<div class="gallery__filters container--wide" role="group"
	aria-label="<?php esc_attr_e( 'Filter by color', 'matize' ); ?>"
	data-count-one="<?php echo esc_attr( _n( '%d image shown', '%d images shown', 1, 'matize' ) ); ?>"
	data-count-other="<?php echo esc_attr( _n( '%d image shown', '%d images shown', 2, 'matize' ) ); ?>">
	<button class="gallery__filter is-active" type="button" data-gallery-filter="all" aria-pressed="true"><?php esc_html_e( 'All colors', 'matize' ); ?></button>
	<?php foreach ( $colors as $color ) : ?>
		<button class="gallery__filter" type="button" data-gallery-filter="<?php echo esc_attr( $color ); ?>" aria-pressed="false"><?php echo esc_html( $labels[ $color ] ); ?></button>
	<?php endforeach; ?>
</div>
<p class="sr-only" aria-live="polite" data-gallery-status></p>
