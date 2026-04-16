<?php get_header(); ?>

<main id="main" class="site-main page-home">

	<?php /* ── Hero ─────────────────────────────────────────────────────── */ ?>
	<?php echo plura_wp_component( get_template_directory() . '/components/hero/manifest.json' ); ?>

	<?php /* ── Services (condensed) ─────────────────────────────────────── */ ?>
	<section class="home-services">
		<div class="container--wide">
			<?php
			echo plura_wp_posts(
				type: 'mtz_service',
				orderby: 'menu_order',
				order: 'ASC',
				class: 'home-services__grid',
				wrap: true,
				link: 1,
				context: 'home-services',
			);
			?>
		</div>
	</section>

	<?php /* ── Statement ────────────────────────────────────────────────── */ ?>
	<?php $statements = get_field( 'mtz_home_statements' ); ?>
	<?php if ( $statements ) : ?>
		<section class="home-statement">
			<div class="home-statement__stage">
				<?php foreach ( $statements as $i => $item ) : ?>
					<div class="home-statement__item" <?php if ( $i === 0 ) echo 'aria-hidden="false"'; else echo 'aria-hidden="true"'; ?>>
						<?php if ( $item['headline'] ) : ?>
							<h2 class="home-statement__headline"><?php echo esc_html( $item['headline'] ); ?></h2>
						<?php endif; ?>
						<?php if ( $item['tagline'] ) : ?>
							<p class="home-statement__tagline"><?php echo esc_html( $item['tagline'] ); ?></p>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		</section>
	<?php endif; ?>

	<?php /* ── Mood gallery ─────────────────────────────────────────────── */ ?>
	<?php $mood = get_field( 'mtz_home_mood' ); ?>
	<?php if ( ! empty( $mood['gallery'] ) ) : ?>
		<section class="mood-gallery">
			<?php if ( $mood['text'] ) : ?>
				<p class="mood-gallery__text container"><?php echo esc_html( $mood['text'] ); ?></p>
			<?php endif; ?>
			<div class="mood-gallery__inner">
				<?php foreach ( $mood['gallery'] as $image ) : ?>
					<figure class="mood-gallery__item">
						<?php echo plura_wp_image( $image['ID'], 'large', [ 'class' => 'mood-gallery__img', 'alt' => esc_attr( $image['alt'] ) ] ); ?>
					</figure>
				<?php endforeach; ?>
			</div>
		</section>
	<?php endif; ?>

</main>

<?php get_footer(); ?>
