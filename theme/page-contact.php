<?php
/**
 * Template Name: Contact
 */
get_header(); ?>

<main id="main" class="site-main page-contact">

	<section class="contact">
		<div class="contact__inner">
			<h1 class="contact__heading"><?php the_title(); ?></h1>
			<?php echo do_shortcode( '[contact-form-7 id="contact" title="Contacto"]' ); ?>
		</div>
	</section>

</main>

<?php get_footer(); ?>
