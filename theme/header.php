<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header id="site-header" class="site-header">
	<div class="site-header__inner">

		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-header__logo" aria-label="<?php bloginfo( 'name' ); ?>">
			<?php
			$logo_id = get_theme_mod( 'custom_logo' );
			if ( $logo_id ) {
				echo wp_get_attachment_image( $logo_id, 'full', false, [ 'class' => 'site-header__logo-img' ] );
			} else {
				bloginfo( 'name' );
			}
			?>
		</a>

		<nav id="site-nav" class="site-nav" aria-label="<?php esc_attr_e( 'Primary Menu', 'matize' ); ?>">
			<?php
			wp_nav_menu( [
				'theme_location' => 'primary',
				'container'      => false,
				'menu_class'     => 'site-nav__list',
			] );
			?>
		</nav>

		<div class="site-header__actions">
			<?php if ( function_exists( 'plura_wpml' ) && plura_wpml() ) : ?>
				<div class="site-header__lang">
					<?php do_action( 'wpml_add_language_selector' ); ?>
				</div>
			<?php endif; ?>

			<button
				class="site-header__menu-toggle"
				aria-label="<?php esc_attr_e( 'Open menu', 'matize' ); ?>"
				aria-expanded="false"
				aria-controls="site-nav"
			>
				<i data-lucide="menu" aria-hidden="true"></i>
				<i data-lucide="x" aria-hidden="true"></i>
			</button>
		</div>

	</div>
</header>
