<?php

if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'admin_menu', function () {
	add_management_page(
		__( 'Image color backfill', 'matize' ),
		__( 'Image color backfill', 'matize' ),
		'manage_options',
		'mtz-image-color-backfill',
		'mtz_render_image_color_backfill_page'
	);
} );

function mtz_render_image_color_backfill_page(): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have permission to access this page.', 'matize' ) );
	}

	$cursor   = absint( $_GET['cursor'] ?? 0 );
	$scanned  = absint( $_GET['scanned'] ?? 0 );
	$failed   = absint( $_GET['failed'] ?? 0 );
	$complete = isset( $_GET['complete'] ) && '1' === $_GET['complete'];
	$batch    = absint( $_GET['batch'] ?? 0 );
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Image color backfill', 'matize' ); ?></h1>
		<p><?php esc_html_e( 'Analyze existing image attachments in batches. Existing manual color overrides are preserved.', 'matize' ); ?></p>
		<?php if ( $batch > 0 ) : ?>
			<div class="notice notice-success"><p>
				<?php
				printf(
					/* translators: 1: images processed in this batch, 2: images processed overall. */
					esc_html__( 'Processed in this batch: %1$d. Scanned in total: %2$d.', 'matize' ),
					$batch,
					$scanned
				);
				?>
			</p></div>
		<?php endif; ?>
		<?php if ( $failed > 0 ) : ?>
			<div class="notice notice-warning"><p>
				<?php
				printf(
					/* translators: %d: number of images that could not be analyzed. */
					esc_html( _n( '%d image could not be analyzed. Check the PHP error log for details.', '%d images could not be analyzed. Check the PHP error log for details.', $failed, 'matize' ) ),
					$failed
				);
				?>
			</p></div>
		<?php endif; ?>
		<?php if ( $complete ) : ?>
			<div class="notice notice-info"><p><?php esc_html_e( 'Backfill complete.', 'matize' ); ?></p></div>
		<?php endif; ?>
		<p>
			<?php
			printf(
				/* translators: 1: images scanned, 2: failed image count. */
				esc_html__( 'Progress — scanned: %1$d, failed: %2$d.', 'matize' ),
				$scanned,
				$failed
			);
			?>
		</p>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="mtz_image_color_backfill">
			<input type="hidden" name="cursor" value="<?php echo esc_attr( (string) $cursor ); ?>">
			<input type="hidden" name="scanned" value="<?php echo esc_attr( (string) $scanned ); ?>">
			<input type="hidden" name="failed" value="<?php echo esc_attr( (string) $failed ); ?>">
			<?php wp_nonce_field( 'mtz_image_color_backfill' ); ?>
			<?php submit_button( __( 'Process next batch', 'matize' ) ); ?>
		</form>
		<p><a href="<?php echo esc_url( add_query_arg( [ 'page' => 'mtz-image-color-backfill', 'cursor' => 0, 'scanned' => 0, 'failed' => 0 ], admin_url( 'tools.php' ) ) ); ?>"><?php esc_html_e( 'Start again from the beginning', 'matize' ); ?></a></p>
	</div>
	<?php
}

add_action( 'admin_post_mtz_image_color_backfill', function () {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have permission to perform this action.', 'matize' ) );
	}
	check_admin_referer( 'mtz_image_color_backfill' );

	$cursor  = absint( $_POST['cursor'] ?? 0 );
	$scanned = absint( $_POST['scanned'] ?? 0 );
	$failed  = absint( $_POST['failed'] ?? 0 );
	$batch_size = 25;
	global $wpdb;
	$attachment_ids = $wpdb->get_col(
		$wpdb->prepare(
			"SELECT ID FROM {$wpdb->posts} WHERE post_type = %s AND post_status = %s AND post_mime_type LIKE %s AND ID > %d ORDER BY ID ASC LIMIT %d",
			'attachment',
			'inherit',
			'image/%',
			$cursor,
			$batch_size
		)
	);
	$batch_failed = 0;

	foreach ( $attachment_ids as $attachment_id ) {
		$color = mtz_detect_image_color( (int) $attachment_id );
		if ( is_wp_error( $color ) ) {
			$batch_failed++;
			error_log( sprintf( 'Matize image color backfill failed for attachment %d: %s', $attachment_id, $color->get_error_message() ) );
			continue;
		}
		update_post_meta( $attachment_id, MTZ_IMAGE_COLOR_META_KEY, $color );
		update_post_meta( $attachment_id, MTZ_IMAGE_COLOR_VERSION_KEY, MTZ_IMAGE_COLOR_VERSION );
	}

	if ( $attachment_ids ) {
		$cursor = (int) end( $attachment_ids );
	}
	$scanned += count( $attachment_ids );
	$failed  += $batch_failed;
	$complete = count( $attachment_ids ) < $batch_size;

	$url = add_query_arg(
		[
			'page'     => 'mtz-image-color-backfill',
			'cursor'   => $cursor,
			'scanned'  => $scanned,
			'failed'   => $failed,
			'batch'    => count( $attachment_ids ) - $batch_failed,
			'complete' => $complete ? 1 : 0,
		],
		admin_url( 'tools.php' )
	);
	wp_safe_redirect( $url );
	exit;
} );
