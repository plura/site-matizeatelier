<?php get_header(); ?>

<main id="main" class="site-main page-home">

	<?php /* ── Hero ─────────────────────────────────────────────────────── */ ?>
	<?php echo plura_wp_component( get_template_directory() . '/components/hero/manifest.json' ); ?>

	<?php /* ── Mood gallery ─────────────────────────────────────────────── */ ?>
	<?php $mood_gallery = get_field( 'mtz_home_mood_gallery' ); ?>
	<?php if ( $mood_gallery ) : ?>
		<section class="mood-gallery">
			<div class="mood-gallery__inner">
				<?php foreach ( $mood_gallery as $image ) : ?>
					<figure class="mood-gallery__item">
						<?php echo wp_get_attachment_image( $image['ID'], 'large', false, [ 'class' => 'mood-gallery__img', 'alt' => esc_attr( $image['alt'] ) ] ); ?>
					</figure>
				<?php endforeach; ?>
			</div>
		</section>
	<?php endif; ?>

	<?php /* ── About (condensed) ────────────────────────────────────────── */ ?>
	<?php $about_page = get_page_by_path( 'about' ); ?>
	<?php if ( $about_page ) : ?>
		<section class="home-about">
			<div class="home-about__inner">
				<h2 class="home-about__heading"><?php echo esc_html( get_the_title( $about_page ) ); ?></h2>
				<?php $excerpt = get_the_excerpt( $about_page ); ?>
				<?php if ( $excerpt ) : ?>
					<div class="home-about__text"><?php echo wp_kses_post( $excerpt ); ?></div>
				<?php endif; ?>
				<a href="<?php echo esc_url( get_permalink( $about_page ) ); ?>" class="home-about__link">
					<?php esc_html_e( 'Sobre nós', 'matize' ); ?>
				</a>
			</div>
		</section>
	<?php endif; ?>

	<?php /* ── Services (condensed) ─────────────────────────────────────── */ ?>
	<section class="home-services">
		<?php
		echo plura_wp_posts(
			type: 'service',
			orderby: 'menu_order',
			order: 'ASC',
			class: 'home-services__grid',
			wrap: true,
			link: 0,
			context: 'home-services',
		);
		?>
	</section>

	<?php /* ── CTA ──────────────────────────────────────────────────────── */ ?>
	<?php get_template_part( 'template-parts/cta' ); ?>

</main>

<?php get_footer(); ?>
