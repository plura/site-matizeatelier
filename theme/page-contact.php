<?php
/**
 * Template Name: Contact
 */

get_header();
get_template_part( 'template-parts/page-header' ); ?>

<main id="main" class="site-main page-contact">

	<section class="contact">
		<div class="contact__inner container">

			<div class="contact__info">
				<?php get_template_part( 'template-parts/contact-info' ); ?>
			</div>

			<div class="contact__form">
				<?php get_template_part( 'template-parts/contact-form' ); ?>
			</div>

		</div>
	</section>

</main>

<?php get_footer(); ?>
