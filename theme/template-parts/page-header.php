<?php
/**
 * Page header — title block for all inner pages. Not used on front-page.php.
 * Pass ['show_intro' => false] to suppress the_content() intro (e.g. page.php).
 */
$show_intro = ( $args['show_intro'] ?? true ) && get_the_content();
?>
<header class="page-header">
	<div class="page-header__inner container">
		<h1 class="page-header__title"><?php the_title(); ?></h1>
		<?php if ( $show_intro ) : ?>
		<p class="page-intro"><?php echo wp_strip_all_tags( get_the_content() ); ?></p>
		<?php endif; ?>
	</div>
</header>
