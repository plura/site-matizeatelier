<?php
/**
 * Template Name: Services
 */
get_header();

$services = new WP_Query( [
	'post_type'      => 'mtz_service',
	'orderby'        => 'menu_order',
	'order'          => 'ASC',
	'posts_per_page' => -1,
	'no_found_rows'  => true,
] );
?>

<main id="main" class="site-main page-services">

	<?php get_template_part( 'template-parts/page-header' ); ?>

	<div class="page-content">

	<?php
	/* TODO: first-pass placement, tune after visual review.
	   Cycled by index since services are CPT-driven (unknown count). Classes
	   defined in pages/services.css as bg-vector--service-{preset}-{n}. */
	$bg_vector_presets = [
		[ [ 'name' => 'furniture-dresser' ],     [ 'name' => 'furniture-floor-lamp' ] ],
		[ [ 'name' => 'furniture-vanity-desk' ], [ 'name' => 'furniture-stool' ]      ],
		[ [ 'name' => 'furniture-armchair' ],    [ 'name' => 'furniture-wardrobe' ]   ],
	];
	$service_index = 0;
	?>

	<?php if ( $services->have_posts() ) : ?>
	<section class="services-section">
		<div class="container">
			<?php while ( $services->have_posts() ) : $services->the_post(); ?>

			<?php
			$tagline     = get_field( 'mtz_service_tagline' );
			$description = get_field( 'mtz_service_description' );
			$images      = get_field( 'mtz_service_gallery' ) ?: [];
			$thumb_id    = get_post_thumbnail_id() ?: null;
			$accent      = mtz_post_accent( get_the_ID() );

			$preset_index = $service_index % count( $bg_vector_presets );
			$vectors      = $bg_vector_presets[ $preset_index ];
			?>

			<?php // id = slug: anchor target for the home-page service cards (mtz_service_url()). ?>
			<article id="<?php echo esc_attr( get_post_field( 'post_name' ) ); ?>" class="content-section content-section--split service-section content-section--<?php echo esc_attr( $accent ); ?>">

				<?php foreach ( $vectors as $i => $vector ) : ?>
					<?php echo mtz_bg_vector( $vector['name'], "bg-vector--service-{$preset_index}-" . ( $i + 1 ) ); ?>
				<?php endforeach; ?>

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

			<?php $service_index++; endwhile; wp_reset_postdata(); ?>
		</div>
	</section>
	<?php endif; ?>

	<?php get_template_part( 'template-parts/brands' ); ?>

	</div><!-- .page-content -->

</main>

<?php get_footer(); ?>
