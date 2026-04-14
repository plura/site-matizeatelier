<?php
/**
 * Template Name: About
 */
get_header();
get_template_part( 'template-parts/page-header' ); ?>

<main id="main" class="site-main page-about">

	<?php
	$sections = [
		'mission'    => [ 'label' => 'mission',    'field' => 'mtz_about_mission'    ],
		'philosophy' => [ 'label' => 'philosophy',  'field' => 'mtz_about_philosophy' ],
		'cv'         => [ 'label' => 'cv',          'field' => 'mtz_about_cv'         ],
	];

	foreach ( $sections as $key => $section ) :
		$data = get_field( $section['field'] );
		if ( ! $data ) continue;
		$text   = $data['text']   ?? '';
		$images = $data['images'] ?? [];
	?>

	<section class="about-section about-section--<?php echo esc_attr( $key ); ?>">
		<div class="container">
			<div class="about-section__inner">

				<div class="about-section__text">
					<?php echo wp_kses_post( $text ); ?>
				</div>

				<?php $cluster = mtz_gallery_cluster( $images ); ?>
			<?php if ( $cluster ) : ?>
				<div class="about-section__cluster">
					<?php echo $cluster; ?>
				</div>
			<?php endif; ?>

			</div>
		</div>
	</section>

	<?php endforeach; ?>

</main>

<?php get_footer(); ?>
