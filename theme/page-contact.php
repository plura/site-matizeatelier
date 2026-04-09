<?php
/**
 * Template Name: Contact
 */
get_header(); ?>

<main id="main" class="site-main page-contact">

	<section class="contact">
		<div class="contact__inner container--narrow">
			<h1 class="contact__heading"><?php the_title(); ?></h1>
			<?php get_template_part( 'template-parts/contact-form' ); ?>
		</div>
	</section>

</main>

<?php get_footer(); ?>
