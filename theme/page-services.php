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

	<?php if ( have_posts() ) : the_post(); ?>
	<?php if ( get_the_content() ) : ?>
	<div class="page-intro container">
		<?php the_content(); ?>
	</div>
	<?php endif; ?>
	<?php endif; ?>

	<?php if ( $services->have_posts() ) : ?>
	<div class="container">
		<?php while ( $services->have_posts() ) : $services->the_post(); ?>

		<?php
		$tagline     = get_field( 'mtz_service_tagline' );
		$description = get_field( 'mtz_service_description' );
		$images      = get_field( 'mtz_service_gallery' ) ?: [];
		$thumb_id    = get_post_thumbnail_id();
		?>

		<section class="content-section service-section">
			<div class="content-section__inner">

				<?php if ( $thumb_id ) : ?>
				<div class="content-section__media service-section__image">
					<?php echo plura_wp_image( $thumb_id, 'large', [ 'class' => 'service-section__img' ] ); ?>
				</div>
				<?php endif; ?>

				<div class="service-section__content">
					<h2 class="service-section__title"><?php the_title(); ?></h2>

					<?php if ( $tagline ) : ?>
					<p class="service-section__tagline"><?php echo esc_html( $tagline ); ?></p>
					<?php endif; ?>

					<?php if ( $description ) : ?>
					<div class="service-section__description"><?php echo wp_kses_post( $description ); ?></div>
					<?php endif; ?>

					<?php $cluster = mtz_gallery_cluster( $images ); ?>
					<?php if ( $cluster ) : ?>
					<div class="service-section__cluster"><?php echo $cluster; ?></div>
					<?php endif; ?>
				</div>

			</div>
		</section>

		<?php endwhile; wp_reset_postdata(); ?>
	</div>
	<?php endif; ?>

	<?php $brands_intro = get_field( 'mtz_services_brands_intro' ); ?>
	<div class="section-header container">
		<h2 class="section-header__title"><?php esc_html_e( 'Brands', 'matize' ); ?></h2>
		<?php if ( $brands_intro ) : ?>
		<p class="page-intro"><?php echo esc_html( $brands_intro ); ?></p>
		<?php endif; ?>
	</div>

	<?php get_template_part( 'template-parts/brands' ); ?>

</main>

<?php get_footer(); ?>
