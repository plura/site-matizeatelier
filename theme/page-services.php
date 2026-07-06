<?php
/**
 * Template Name: Services
 */
get_header();
get_template_part( 'template-parts/page-header' );

$services = new WP_Query( [
	'post_type'      => 'mtz_service',
	'orderby'        => 'menu_order',
	'order'          => 'ASC',
	'posts_per_page' => -1,
	'no_found_rows'  => true,
] );
?>

<main id="main" class="site-main page-services">

	<?php if ( $services->have_posts() ) : ?>
	<section class="services-section">
		<div class="container">
			<?php
			/* TODO: replace with ACF colour field on the service CPT */
			$accent_classes = [ 'content-section--teal', 'content-section--gold', 'content-section--coral', 'content-section--sage' ];
			$section_index  = 0;
			?>
			<?php while ( $services->have_posts() ) : $services->the_post(); ?>

			<?php
			$tagline     = get_field( 'mtz_service_tagline' );
			$description = get_field( 'mtz_service_description' );
			$images      = get_field( 'mtz_service_gallery' ) ?: [];
			$thumb_id    = get_post_thumbnail_id();
			?>

			<article class="content-section content-section--split service-section <?php echo $accent_classes[ $section_index % count( $accent_classes ) ]; ?>">
				<div class="content-section__inner">

					<div class="content-section__body">
						<h2 class="service-section__title section-header__title"><?php the_title(); ?></h2>

						<?php if ( $tagline ) : ?>
						<p class="service-section__tagline"><?php echo esc_html( $tagline ); ?></p>
						<?php endif; ?>

						<?php if ( $description ) : ?>
						<div class="service-section__description section-body prose"><?php echo wp_kses_post( $description ); ?></div>
						<?php endif; ?>
					</div>

					<?php echo mtz_img_stack( $images, $thumb_id ); ?>

				</div>
			</article>

			<?php $section_index++; endwhile; wp_reset_postdata(); ?>
		</div>
	</section>
	<?php endif; ?>

	<?php get_template_part( 'template-parts/brands' ); ?>

</main>

<?php get_footer(); ?>
