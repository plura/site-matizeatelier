<?php
/**
 * Page header — title block for all inner pages. Not used on front-page.php.
 */
$intro = get_field( 'mtz_page_intro' );

// A word over 8 letters won't fit the watermark's full size on a phone —
// see .page-header__title--long (layout.css).
$words   = preg_split( '/\s+/u', html_entity_decode( wp_strip_all_tags( get_the_title() ), ENT_QUOTES, 'UTF-8' ), -1, PREG_SPLIT_NO_EMPTY );
$is_long = max( [ 0, ...array_map( 'mb_strlen', $words ) ] ) > 8;
?>
<header class="page-header">
	<div class="page-header__inner container">
		<h1 class="page-header__title<?php echo $is_long ? ' page-header__title--long' : ''; ?>"><?php the_title(); ?></h1>
		<?php if ( $intro ) : ?>
		<p class="page-intro"><?php echo esc_html( $intro ); ?></p>
		<?php endif; ?>
	</div>
</header>
