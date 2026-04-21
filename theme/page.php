<?php
get_header();
get_template_part( 'template-parts/page-header' ); ?>

<main id="main" class="site-main page-<?php echo esc_attr( get_post_field( 'post_name' ) ); ?>">
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
	<section class="content-section">
		<div class="container">
			<div class="section-body">
				<?php the_content(); ?>
			</div>
		</div>
	</section>
	<?php endwhile; endif; ?>
</main>

<?php get_footer(); ?>
