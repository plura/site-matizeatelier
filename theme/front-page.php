<?php get_header(); ?>

<main id="main" class="site-main page-home">

	<?php /* ── Hero ─────────────────────────────────────────────────────── */ ?>
	<section class="hero" aria-label="<?php esc_attr_e( 'Hero', 'matize' ); ?>">

		<?php $hero_video = get_field( 'hero_video' ); ?>
		<?php if ( $hero_video ) : ?>
			<video
				class="hero__video"
				src="<?php echo esc_url( $hero_video['url'] ); ?>"
				autoplay
				muted
				loop
				playsinline
				aria-hidden="true"
			></video>
		<?php endif; ?>

		<div class="hero__logo" aria-hidden="true">
			<?php get_template_part( 'templates/svg/logo' ); ?>
		</div>

	</section>

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
	<?php
	$services = new WP_Query( [
		'post_type'      => 'service',
		'posts_per_page' => -1,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
		'no_found_rows'  => true,
	] );
	?>
	<?php if ( $services->have_posts() ) : ?>
		<section class="home-services">
			<div class="home-services__inner">
				<?php while ( $services->have_posts() ) : $services->the_post(); ?>
					<article class="home-services__item">
						<?php if ( has_post_thumbnail() ) : ?>
							<div class="home-services__img">
								<?php the_post_thumbnail( 'medium', [ 'alt' => get_the_title() ] ); ?>
							</div>
						<?php endif; ?>
						<h3 class="home-services__title"><?php the_title(); ?></h3>
						<?php $desc = get_field( 'service_description' ); ?>
						<?php if ( $desc ) : ?>
							<div class="home-services__desc"><?php echo wp_kses_post( $desc ); ?></div>
						<?php endif; ?>
					</article>
				<?php endwhile; wp_reset_postdata(); ?>
			</div>
		</section>
	<?php endif; ?>

	<?php /* ── CTA (global) ──────────────────────────────────────────────── */ ?>
	<?php
	$cta_heading = get_field( 'cta_heading', 'option' );
	$cta_text    = get_field( 'cta_text', 'option' );
	$cta_link    = get_field( 'cta_link', 'option' );
	?>
	<?php if ( $cta_heading || $cta_link ) : ?>
		<section class="cta">
			<div class="cta__inner">
				<?php if ( $cta_heading ) : ?>
					<h2 class="cta__heading"><?php echo esc_html( $cta_heading ); ?></h2>
				<?php endif; ?>
				<?php if ( $cta_text ) : ?>
					<p class="cta__text"><?php echo wp_kses_post( $cta_text ); ?></p>
				<?php endif; ?>
				<?php if ( $cta_link ) : ?>
					<a href="<?php echo esc_url( $cta_link['url'] ); ?>" class="cta__btn">
						<?php echo esc_html( $cta_link['title'] ); ?>
					</a>
				<?php endif; ?>
			</div>
		</section>
	<?php endif; ?>

</main>

<?php get_footer(); ?>
