<?php
$html = plura_wp_posts(
	type:      'mtz_brand',
	orderby:   'menu_order',
	order:     'ASC',
	class:     'brands__grid',
	wrap:      true,
	link:      1,
	read_more: false,
	context:   'brands-grid',
);

if ( ! $html ) return;

$intro = get_field( 'mtz_services_brands_intro' );
?>

<section class="brands">
	<div class="container">
		<header class="section-header">
			<h2 class="section-header__title"><?php esc_html_e( 'Brands', 'matize' ); ?></h2>
			<?php if ( $intro ) : ?>
			<p class="page-intro title-intro"><?php echo esc_html( wp_strip_all_tags( $intro ) ); ?></p>
			<?php endif; ?>
		</header>
		<?php echo $html; ?>
	</div>
</section>
