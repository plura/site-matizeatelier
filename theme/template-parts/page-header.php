<?php
/**
 * Page header — common title section for all inner pages.
 * Not used on front-page.php.
 * Outputs page intro (the_content) when present, grouped with the title.
 */
$has_intro = have_posts() && get_the_content();
?>
<header class="page-header">
	<div class="page-header__inner container">
		<h1 class="page-header__title"><?php the_title(); ?></h1>
		<?php if ( $has_intro ) : ?>
		<p class="page-intro"><?php echo wp_strip_all_tags( get_the_content() ); ?></p>
		<?php endif; ?>
	</div>
</header>
