<?php
/**
 * The main plugin class.
 */

declare(strict_types=1);

namespace Imageshop\WordPress;

use Imageshop\WordPress\REST\Controller;

/**
 * Imageshop Media Library main class.
 */
class Plugin {

	private static $instance;

	public $onboarding_completed = null;

	/**
	 * Class constructor.
	 */
	public function __construct() {

		// Register class handlers used throughout the plugin.
		new Controller();
		new Settings();
	}

	/**
	 * Flushes any caches that rely on data from the Imageshop API.
	 *
	 * When a new API key is provided, old cache data is no longer relevant,
	 * this strongly ties into things such as interfaces and categories.
	 *
	 * @return void
	 */
	public function flush_api_related_caches() {
		// Remove the old interface setting, these are unique to their individual API keys.
		delete_option( 'imageshop_upload_interface' );
	}

	/**
	 * Check the onboarding state, and if required settings are in place.
	 *
	 * @return bool
	 */
	public function onboarding_completed() {
		if ( null === $this->onboarding_completed ) {
			$completed = false;

			$interface = \get_option( 'imageshop_upload_interface', '' );
			$api_key   = \get_option( 'imageshop_api_key', '' );

			if ( ! empty( $interface ) && ! empty( $api_key ) ) {
				$completed = true;
			}

			$this->onboarding_completed = $completed;
		}

		return $this->onboarding_completed;
	}

	/**
	 * Return a singleton instance of this class.
	 *
	 * @return self
	 */
	public static function get_instance() {
		if ( ! self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Initiate all needed classes.
	 */
	public function start() {
		Attachment::get_instance();
		Library::get_instance();
		Onboarding::get_instance();
		Search::get_instance();
		Sync::get_instance();
	}
}
