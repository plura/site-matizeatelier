<?php

// ─── Autoload ────────────────────────────────────────────────────────────────

foreach ( [ 'core', 'hooks' ] as $dir ) {
	foreach ( glob( get_template_directory() . '/includes/' . $dir . '/*.php' ) as $file ) {
		require_once $file;
	}
}
