<?php
/**
 * Template Name: Services
 */
get_header();
get_template_part( 'template-parts/page-header' ); ?>

<main id="main" class="site-main page-services">

	<div class="container">
		<?php
		echo plura_wp_posts(
			type: 'mtz_service',
			orderby: 'menu_order',
			order: 'ASC',
			class: 'services__grid',
			wrap: true,
			link: -1,
			context: 'page-services',
		);
		?>
	</div>

	<?php get_template_part( 'template-parts/brands' ); ?>

</main>

<?php get_footer(); ?>
