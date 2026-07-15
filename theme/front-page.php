<?php get_header(); ?>

<main id="main" class="site-main page-home">

	<?php /* ── Hero ─────────────────────────────────────────────────────── */ ?>
	<?php echo plura_wp_component( get_template_directory() . '/components/hero/manifest.json' ); ?>

	<?php /* ── Services ──────────────────────────────────────────────────── */ ?>
	<section class="home-services">
		<div class="home-services__grid">
			<?php
			echo plura_wp_posts(
				type: 'mtz_service',
				orderby: 'menu_order',
				order: 'ASC',
				link: 1,
				context: 'home-services',
				wrap: false,
			);
			?>
			<div class="home-services__spacer"></div>
		</div>
	</section>

	<?php /* ── Statement ────────────────────────────────────────────────── */ ?>
	<?php $statements = get_field( 'mtz_home_statements' ); ?>
	<?php if ( $statements ) : ?>
		<section class="home-statement" data-mtz-theme="gold">
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
		<?php $mood_count = count( $mood['gallery'] ); ?>
		<section class="mood-gallery" data-mtz-theme="sage">
			<div class="mood-gallery__layout">
				<div class="mood-gallery__col--text">
					<?php if ( $mood['text'] ) : ?>
						<h2 class="mood-gallery__text"><?php echo esc_html( $mood['text'] ); ?></h2>
					<?php endif; ?>
					<div class="mood-gallery__progress">
						<span class="mood-gallery__counter">01 / <?php echo esc_html( str_pad( (string) $mood_count, 2, '0', STR_PAD_LEFT ) ); ?></span>
						<div class="mood-gallery__track"><div class="mood-gallery__fill"></div></div>
					</div>
				</div>
				<div class="mood-gallery__col--images">
					<div class="mood-gallery__deck">
						<?php foreach ( $mood['gallery'] as $i => $image ) : ?>
							<figure class="mood-gallery__item">
								<div class="mood-gallery__frame">
									<?php echo plura_wp_image( $image['ID'], 'large', [ 'class' => 'mood-gallery__img', 'alt' => esc_attr( $image['alt'] ) ] ); ?>
								</div>
								<figcaption class="mood-gallery__caption">
									<span class="mood-gallery__label"><?php echo esc_html( $image['title'] ); ?></span>
									<span class="mood-gallery__num"><?php echo esc_html( str_pad( (string) ( $i + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span>
								</figcaption>
							</figure>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</section>
	<?php endif; ?>

</main>

<?php get_footer(); ?>
