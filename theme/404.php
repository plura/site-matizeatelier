<?php get_header(); ?>

<main id="main" class="site-main page-404">

	<section class="error-404">
		<div class="error-404__inner">
			<h1 class="error-404__heading"><?php esc_html_e( 'Page not found', 'matize' ); ?></h1>
			<p class="error-404__text"><?php esc_html_e( 'The page you are looking for does not exist or has been removed.', 'matize' ); ?></p>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="error-404__link">
				<?php esc_html_e( 'Back to home', 'matize' ); ?>
			</a>
		</div>
	</section>

</main>

<?php get_footer(); ?>
