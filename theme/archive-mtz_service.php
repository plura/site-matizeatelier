<?php get_header(); ?>

<main id="main" class="site-main archive-services">

	<?php
	echo plura_wp_posts(
		type: 'mtz_service',
		orderby: 'menu_order',
		order: 'ASC',
		class: 'services__grid',
		wrap: true,
		link: 0,
		context: 'archive-services',
	);
	?>

</main>

<?php get_footer(); ?>
