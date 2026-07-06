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

	/* TODO: first-pass placement, tune after visual review */
	$bg_vectors = [
		'mission'    => [
			[ 'name' => 'furniture-dresser',    'vars' => [ '--x' => '4%',  '--y' => '65%', '--scale' => '.9',  '--rot' => '-6deg', '--opacity' => '.07' ] ],
			[ 'name' => 'furniture-floor-lamp', 'vars' => [ '--x' => '82%', '--y' => '-8%', '--scale' => '1.3', '--rot' => '4deg',  '--opacity' => '.06' ] ],
		],
		'philosophy' => [
			[ 'name' => 'furniture-vanity-desk', 'vars' => [ '--x' => '78%', '--y' => '70%', '--scale' => '1.1', '--rot' => '-4deg', '--opacity' => '.07' ] ],
			[ 'name' => 'furniture-mirror',      'vars' => [ '--x' => '6%',  '--y' => '-10%', '--scale' => '1.4', '--rot' => '6deg',  '--opacity' => '.06' ] ],
		],
		'cv'         => [
			[ 'name' => 'furniture-armchair', 'vars' => [ '--x' => '5%',  '--y' => '-6%', '--scale' => '1.2', '--rot' => '-5deg', '--opacity' => '.07' ] ],
			[ 'name' => 'furniture-wardrobe',  'vars' => [ '--x' => '80%', '--y' => '55%', '--scale' => '1.3', '--rot' => '3deg',  '--opacity' => '.06' ] ],
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

		<?php foreach ( $bg_vectors[ $key ] ?? [] as $vector ) : ?>
			<?php echo mtz_bg_vector( $vector['name'], $vector['vars'] ); ?>
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
