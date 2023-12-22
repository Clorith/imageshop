<?php
/**
 * Plugin Name: Imageshop DAM Connector
 * Plugin URI:
 * Description: Use the Imageshop media library as your companys one source for all media.
 * Version: 1.1.0-beta1
 * Author: Imageshop
 * Author URI: https://imageshop.org
 * License: MIT
 * Text Domain: imageshop-dam-connector
 * Domain Path: /languages
 * Requires PHP: 5.6
 * Requires at least: 5.6
 */

declare( strict_types = 1 );

namespace Imageshop\WordPress;

\defined( 'ABSPATH' ) || exit;

\define( 'IMAGESHOP_ABSPATH', __DIR__ );
\define( 'IMAGESHOP_PLUGIN_BASE_NAME', __FILE__ );

/**
 * Register a custom autoloader.
 *
 * The custom autoloader lets the plugin include files as they are needed, based on the namespace and class name used.
 * This ensures we only load what is needed at any given time, and that we don't have to manually include files.
 */
\spl_autoload_register(
	function( $class ) {
		$prefix = __NAMESPACE__ . '\\';
		$base_dir = __DIR__ . '/includes/';
		$len = \strlen( $prefix );
		if ( \strncmp( $prefix, $class, $len ) !== 0 ) {
			return;
		}
		$relative_class = \substr( $class, $len );
		$file = $base_dir . \str_replace( '\\', '/', $relative_class ) . '.php';

		if ( \file_exists( $file ) ) {
			require_once $file;
		}
	}
);

// Validate that the plugin is compatible when being activated.
\register_activation_hook(
	__FILE__,
	function() {
		if ( \is_admin() && ( ! \defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {
			if ( \version_compare( PHP_VERSION, '5.6', '<' ) ) {
				// Ensure the `plugin.php` file is loaded, as it contains the `deactivate_plugins` function.
				require_once ABSPATH . DIRECTORY_SEPARATOR . 'wp-admin' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'plugin.php';
				\deactivate_plugins( __FILE__ );

				\wp_die(
					\sprintf(
						// translators: %s is the PHP version.
						__(
							'The Imageshop Media Library plugin requires PHP version 5.6 or higher. This site uses PHP version %s, which has caused the plugin to be automatically deactivated.',
							'imageshop-dam-connector'
						),
						PHP_VERSION
					)
				);
			}
		}
	}
);

if ( \class_exists( 'WP_CLI' ) ) {
	require_once __DIR__ . '/includes/CLI/class-meta.php';
	require_once __DIR__ . '/includes/CLI/class-media.php';
	require_once __DIR__ . '/includes/CLI/class-duplicates.php';
}

$isml = Imageshop::get_instance();
$isml->start();
