<?php
/**
 * Page header — title block for all inner pages. Not used on front-page.php.
 */
$intro = get_field( 'mtz_page_intro' );
?>
<header class="page-header">
	<div class="page-header__inner container">
		<h1 class="page-header__title"><?php the_title(); ?></h1>
		<?php if ( $intro ) : ?>
		<p class="page-intro"><?php echo esc_html( $intro ); ?></p>
		<?php endif; ?>
	</div>
</header>
