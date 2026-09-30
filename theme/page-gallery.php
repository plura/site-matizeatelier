<?php
/**
 * Template Name: Gallery
 */
get_header(); ?>

<main id="main" class="site-main page-gallery">

	<?php get_template_part( 'template-parts/page-header' ); ?>

	<div class="page-content">
	<?php $gallery = get_field( 'mtz_gallery_items' ); ?>
	<?php if ( $gallery ) : ?>
		<section class="gallery">
			<?php
			$gallery_items = [];
			foreach ( $gallery as $image ) {
				if ( ! is_array( $image ) ) {
					$image = get_post( (int) $image );
					if ( ! $image ) continue;
					$image = [ 'ID' => $image->ID, 'url' => wp_get_attachment_url( $image->ID ), 'caption' => '' ];
				}
				$image['color']  = mtz_get_image_color( (int) $image['ID'] );
				$gallery_items[] = $image;
			}
			get_template_part( 'template-parts/gallery-filters', null, [ 'colors' => array_column( $gallery_items, 'color' ) ] );
			?>
			<div class="gallery__grid container--wide">
				<?php foreach ( $gallery_items as $image ) : ?>
					<figure class="gallery__item" data-gallery-color="<?php echo esc_attr( $image['color'] ); ?>">
						<a href="<?php echo esc_url( $image['url'] ); ?>" data-fancybox="gallery" data-caption="<?php echo esc_attr( $image['caption'] ?? '' ); ?>">
							<?php echo plura_wp_image( attachment: $image['ID'], size: 'large', atts: [ 'class' => 'gallery__img' ] ); ?>
						</a>
					</figure>
				<?php endforeach; ?>
			</div>
		</section>
	<?php endif; ?>
	</div><!-- .page-content -->

</main>

<?php get_footer(); ?>
