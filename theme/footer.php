<?php
?>

<footer id="site-footer" class="site-footer">
	<div class="site-footer__inner container">

		<div class="site-footer__brand">
			<?php echo mtz_logo( 'site-footer__logo' ); ?>
		</div>

		<?php get_template_part( 'template-parts/contact-info' ); ?>

		<div class="site-footer__bottom">

			<span class="site-footer__copy">
				&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>
			</span>

			<nav class="site-footer__nav" aria-label="<?php esc_attr_e( 'Footer Menu', 'matize' ); ?>">
				<?php
				wp_nav_menu( [
					'theme_location' => 'footer',
					'container'      => false,
					'menu_class'     => 'site-footer__nav-list',
				] );
				?>
			</nav>

			<a href="https://plura.pt" class="site-footer__credit" target="_blank" rel="noopener noreferrer">
				<?php esc_html_e( 'Built by Plura', 'matize' ); ?>
			</a>

		</div>

	</div>
</footer>

<?php get_template_part( 'template-parts/cta' ); ?>

<?php wp_footer(); ?>
</body>
</html>
