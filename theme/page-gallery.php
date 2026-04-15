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
						<a href="<?php echo esc_url( $image['url'] ); ?>" data-fancybox="gallery" data-caption="<?php echo esc_attr( $image['caption'] ?? '' ); ?>">
							<?php echo plura_wp_image( $image['ID'], 'large', [ 'class' => 'gallery__img' ] ); ?>
						</a>
					</figure>
				<?php endforeach; ?>
			</div>
		</section>
	<?php endif; ?>

</main>

<?php get_footer(); ?>
