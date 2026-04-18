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

		<?php echo mtz_logo( 'site-header__logo' ); ?>

		<nav id="site-nav" class="site-nav" aria-label="<?php esc_attr_e( 'Primary Menu', 'matize' ); ?>">
			<?php
			wp_nav_menu( [
				'theme_location' => 'primary',
				'container'      => false,
				'menu_class'     => 'site-nav__list',
			] );
			?>
			<?php get_template_part( 'template-parts/social-links' ); ?>
		</nav>

		<div class="site-header__actions">
			<button
				class="site-header__menu-toggle"
				aria-label="<?php esc_attr_e( 'Open menu', 'matize' ); ?>"
				aria-expanded="false"
				aria-controls="site-nav"
			>
				<span class="icon-open"><i data-lucide="menu" aria-hidden="true"></i></span>
				<span class="icon-close"><i data-lucide="x" aria-hidden="true"></i></span>
			</button>
		</div>

	</div>
</header>
