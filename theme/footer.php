<?php
?>

<footer id="site-footer" class="site-footer">
	<div class="site-footer__inner container">

		<div class="site-footer__brand">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php bloginfo( 'name' ); ?>">
				<?php bloginfo( 'name' ); ?>
			</a>
		</div>

		<?php get_template_part( 'template-parts/contact-info' ); ?>

		<nav class="site-footer__nav" aria-label="<?php esc_attr_e( 'Footer Menu', 'matize' ); ?>">
			<?php
			wp_nav_menu( [
				'theme_location' => 'footer',
				'container'      => false,
				'menu_class'     => 'site-footer__list',
			] );
			?>
		</nav>

		<p class="site-footer__copy">
			<span>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?></span>
			<a href="https://plura.pt" class="site-footer__credit" target="_blank" rel="noopener noreferrer">
				<?php esc_html_e( 'Built by Plura', 'matize' ); ?>
			</a>
		</p>

	</div>
</footer>

<?php get_template_part( 'template-parts/cta' ); ?>

<?php wp_footer(); ?>
</body>
</html>
