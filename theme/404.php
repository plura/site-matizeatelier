<?php get_header(); ?>

<main id="main" class="site-main page-404">

	<section class="error-404">
		<div class="error-404__inner">
			<h1 class="error-404__heading"><?php esc_html_e( 'Página não encontrada', 'matize' ); ?></h1>
			<p class="error-404__text"><?php esc_html_e( 'A página que procura não existe ou foi removida.', 'matize' ); ?></p>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="error-404__link">
				<?php esc_html_e( 'Voltar ao início', 'matize' ); ?>
			</a>
		</div>
	</section>

</main>

<?php get_footer(); ?>
