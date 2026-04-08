<?php
/**
 * Template Name: Services
 */
get_header(); ?>

<main id="main" class="site-main page-services">

	<?php
	echo plura_wp_posts(
		type: 'mtz_service',
		orderby: 'menu_order',
		order: 'ASC',
		class: 'services__grid',
		wrap: true,
		link: 0,
		context: 'page-services',
	);
	?>

</main>

<?php get_footer(); ?>
