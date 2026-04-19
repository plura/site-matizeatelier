<?php
/**
 * Template Name: About
 */
get_header();
get_template_part( 'template-parts/page-header' ); ?>

<main id="main" class="site-main page-about">

	<?php
	$sections = [
		'mission'    => [ 'title' => __( 'Mission',    'matize' ), 'field' => 'mtz_about_mission'    ],
		'philosophy' => [ 'title' => __( 'Philosophy', 'matize' ), 'field' => 'mtz_about_philosophy' ],
		'cv'         => [ 'title' => __( 'Curriculum', 'matize' ), 'field' => 'mtz_about_cv'         ],
	];

	foreach ( $sections as $key => $section ) :
		$data = get_field( $section['field'] );
		if ( ! $data ) continue;
		$text   = $data['text']   ?? '';
		$images = $data['images'] ?? [];
	?>

	<section class="content-section about-section about-section--<?php echo esc_attr( $key ); ?>">
		<div class="container">
			<div class="content-section__inner">

				<div class="about-section__text">
					<h2 class="about-section__title section-header__title"><?php echo esc_html( $section['title'] ); ?></h2>
					<div class="section-body"><?php echo wp_kses_post( $text ); ?></div>
				</div>

				<?php $cluster = mtz_gallery_cluster( $images ); ?>
			<?php if ( $cluster ) : ?>
				<div class="content-section__media about-section__cluster">
					<?php echo $cluster; ?>
				</div>
			<?php endif; ?>

			</div><!-- .content-section__inner -->
		</div>
	</section>

	<?php endforeach; ?>

</main>

<?php get_footer(); ?>
