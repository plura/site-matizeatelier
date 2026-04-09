<?php
get_header();
get_template_part( 'template-parts/page-header' ); ?>

<main id="main" class="site-main page-<?php echo esc_attr( get_post_field( 'post_name' ) ); ?>">
</main>

<?php get_footer(); ?>
