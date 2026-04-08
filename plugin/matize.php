<?php
/**
 * Plugin Name: Matize
 * Plugin URI:  https://matizeatelier.pt
 * Description: Site-specific plugin for Matize Atelier. Registers custom post types and taxonomies.
 * Version:     1.0.0
 * Author:      Plura
 * Author URI:  https://plura.pt
 * Text Domain: matize
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'init', function () {
	load_plugin_textdomain( 'matize', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
} );

// ─── Autoload ────────────────────────────────────────────────────────────────

foreach ( glob( plugin_dir_path( __FILE__ ) . 'includes/**/*.php' ) as $file ) {
	require_once $file;
}
