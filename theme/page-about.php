<?php
/**
 * Template Name: About
 */
get_header(); ?>

<main id="main" class="site-main page-about">

	<?php /* ── Mission ──────────────────────────────────────────────────── */ ?>
	<?php $mission = get_field( 'mtz_about_mission' ); ?>
	<?php if ( $mission ) : ?>
		<section class="about-mission">
			<div class="about-mission__inner container--narrow">
				<?php echo wp_kses_post( $mission ); ?>
			</div>
		</section>
	<?php endif; ?>

	<?php /* ── Philosophy ───────────────────────────────────────────────── */ ?>
	<?php $philosophy = get_field( 'mtz_about_philosophy' ); ?>
	<?php if ( $philosophy ) : ?>
		<section class="about-philosophy">
			<div class="about-philosophy__inner container--narrow">
				<?php echo wp_kses_post( $philosophy ); ?>
			</div>
		</section>
	<?php endif; ?>

	<?php /* ── CV ───────────────────────────────────────────────────────── */ ?>
	<?php $cv = get_field( 'mtz_about_cv' ); ?>
	<?php if ( $cv ) : ?>
		<section class="about-cv">
			<div class="about-cv__inner container--narrow">
				<?php echo wp_kses_post( $cv ); ?>
			</div>
		</section>
	<?php endif; ?>

</main>

<?php get_footer(); ?>
