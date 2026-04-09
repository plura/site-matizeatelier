<?php
/**
 * Template Name: Gallery
 */
get_header();
get_template_part( 'template-parts/page-header' ); ?>

<main id="main" class="site-main page-gallery">

	<?php $gallery = get_field( 'mtz_gallery_items' ); ?>
	<?php if ( $gallery ) : ?>
		<section class="gallery">
			<div class="gallery__grid container--wide">
				<?php foreach ( $gallery as $image ) : ?>
					<figure class="gallery__item">
						<?php echo wp_get_attachment_image( $image['ID'], 'large', false, [
							'class' => 'gallery__img',
							'alt'   => esc_attr( $image['alt'] ),
						] ); ?>
					</figure>
				<?php endforeach; ?>
			</div>
		</section>
	<?php endif; ?>

</main>

<?php get_footer(); ?>
