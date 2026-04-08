<?php get_header(); ?>

<main id="main" class="site-main page-home">

	<?php /* ── Hero ─────────────────────────────────────────────────────── */ ?>
	<?php echo plura_wp_component( get_template_directory() . '/components/hero/manifest.json' ); ?>

	<?php /* ── Mood gallery ─────────────────────────────────────────────── */ ?>
	<?php $mood_gallery = get_field( 'home_mood_gallery' ); ?>
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
	<?php
	$about_heading = get_field( 'home_about_heading' );
	$about_text    = get_field( 'home_about_text' );
	$about_link    = get_field( 'home_about_link' );
	?>
	<?php if ( $about_heading || $about_text ) : ?>
		<section class="home-about">
			<div class="home-about__inner">
				<?php if ( $about_heading ) : ?>
					<h2 class="home-about__heading"><?php echo esc_html( $about_heading ); ?></h2>
				<?php endif; ?>
				<?php if ( $about_text ) : ?>
					<div class="home-about__text"><?php echo wp_kses_post( $about_text ); ?></div>
				<?php endif; ?>
				<?php if ( $about_link ) : ?>
					<a href="<?php echo esc_url( $about_link['url'] ); ?>" class="home-about__link">
						<?php echo esc_html( $about_link['title'] ); ?>
					</a>
				<?php endif; ?>
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
	<?php
	$cta_heading = get_field( 'cta_heading', 'option' );
	$cta_text    = get_field( 'cta_text', 'option' );
	$cta_label   = get_field( 'cta_label', 'option' );
	?>
	<?php if ( $cta_heading ) : ?>
		<section class="cta">
			<div class="cta__inner">
				<h2 class="cta__heading"><?php echo esc_html( $cta_heading ); ?></h2>
				<?php if ( $cta_text ) : ?>
					<p class="cta__text"><?php echo wp_kses_post( $cta_text ); ?></p>
				<?php endif; ?>
				<button
					class="cta__btn"
					popovertarget="contact-modal"
				>
					<?php echo esc_html( $cta_label ?: __( 'Contactar', 'matize' ) ); ?>
				</button>
			</div>
		</section>
	<?php endif; ?>

	<?php /* ── Contact modal ────────────────────────────────────────────── */ ?>
	<div id="contact-modal" popover>
		<button class="contact-modal__close" popovertarget="contact-modal" popovertargetaction="hide" aria-label="<?php esc_attr_e( 'Fechar', 'matize' ); ?>">
			&times;
		</button>
		<?php echo do_shortcode( '[contact-form-7 id="contact" title="Contacto"]' ); ?>
	</div>

</main>

<?php get_footer(); ?>
