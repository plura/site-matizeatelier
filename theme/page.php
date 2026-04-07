<?php get_header(); ?>

<main id="main" class="site-main page-<?php echo esc_attr( get_post_field( 'post_name' ) ); ?>">
	<?php the_title( '<h1 class="page-title">', '</h1>' ); ?>
</main>

<?php get_footer(); ?>
