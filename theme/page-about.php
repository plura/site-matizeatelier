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

	/* TODO: first-pass placement, tune after visual review.
	   Placement classes auto-generated as bg-vector--{key}-{n}, defined in pages/about.css */
	$bg_vectors = [
		'mission'    => [
			[ 'name' => 'furniture-dresser' ],
			[ 'name' => 'furniture-floor-lamp' ],
		],
		'philosophy' => [
			[ 'name' => 'furniture-vanity-desk' ],
			[ 'name' => 'furniture-mirror' ],
		],
		'cv'         => [
			[ 'name' => 'furniture-armchair' ],
			[ 'name' => 'furniture-wardrobe' ],
		],
	];

	foreach ( $sections as $key => $section ) :
		$data = get_field( $section['field'] );
		if ( ! $data ) continue;
		$text   = $data['text']   ?? '';
		$images = $data['images'] ?? [];
		$accent = $data['accent'] ?? '';
	?>

	<section class="content-section content-section--split about-section about-section--<?php echo esc_attr( $key ); ?> <?php echo $accent ? 'content-section--' . esc_attr( $accent ) : ''; ?>">

		<?php foreach ( $bg_vectors[ $key ] ?? [] as $i => $vector ) : ?>
			<?php echo mtz_bg_vector( $vector['name'], "bg-vector--{$key}-" . ( $i + 1 ) ); ?>
		<?php endforeach; ?>

		<div class="container">
			<div class="content-section__inner">

				<div class="content-section__body">
					<h2 class="about-section__title section-header__title"><?php echo esc_html( $section['title'] ); ?></h2>
					<div class="section-body prose"><?php echo wp_kses_post( $text ); ?></div>
				</div>

			<?php echo mtz_img_stack( $images ); ?>

			</div><!-- .content-section__inner -->
		</div>
	</section>

	<?php endforeach; ?>

</main>

<?php get_footer(); ?>
