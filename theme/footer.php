<footer id="site-footer" class="site-footer">
	<div class="site-footer__inner">

		<div class="site-footer__brand">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php bloginfo( 'name' ); ?>">
				<?php bloginfo( 'name' ); ?>
			</a>
		</div>

		<nav class="site-footer__nav" aria-label="<?php esc_attr_e( 'Footer Menu', 'matize' ); ?>">
			<?php
			wp_nav_menu( [
				'theme_location' => 'footer',
				'container'      => false,
				'menu_class'     => 'site-footer__list',
			] );
			?>
		</nav>

		<div class="site-footer__social">
			<?php
			$instagram = get_field( 'mtz_social_instagram', 'option' );
			if ( $instagram ) : ?>
				<a href="<?php echo esc_url( $instagram ); ?>" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
					Instagram
				</a>
			<?php endif; ?>
		</div>

		<p class="site-footer__copy">
			&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>
		</p>

	</div>
</footer>

<?php get_template_part( 'template-parts/cta' ); ?>

<?php wp_footer(); ?>
</body>
</html>
