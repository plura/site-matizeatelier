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
?>

<section class="brands">
	<div class="container">
		<?php echo $html; ?>
	</div>
</section>
