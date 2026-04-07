<?php

// ─── Autoload ────────────────────────────────────────────────────────────────

foreach ( glob( get_template_directory() . '/includes/*.php' ) as $file ) {
	require_once $file;
}
