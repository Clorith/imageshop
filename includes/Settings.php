<?php

namespace Imageshop\WordPress;

class Settings {

	public function __construct() {
		\add_action( 'admin_menu', array( $this, 'register_menu' ) );
		\add_action( 'admin_init', array( $this, 'register_settings' ) );
		\add_action( 'admin_enqueue_scripts', array( $this, 'register_assets' ) );
		\add_action( 'update_option_imageshop_api_key', array( $this, 'flush_api_related_caches' ) );
	}

	public function register_assets() {
		$screen = \get_current_screen();

		if ( 'settings_page_imageshop-settings' !== $screen->base ) {
			return;
		}

		$asset = require_once IMAGESHOP_ABSPATH . '/build/settings.asset.php';

		\wp_enqueue_style( 'imageshop-settings', \plugins_url( 'build/settings.css', IMAGESHOP_PLUGIN_BASE_NAME ), array(), $asset['version'] );
		\wp_enqueue_script(
			'imageshop-settings',
			\plugins_url( 'build/settings.js', IMAGESHOP_PLUGIN_BASE_NAME ),
			$asset['dependencies'],
			$asset['version'],
			true
		);
	}

	/**
	 * Register settings.
	 */
	public function register_settings() {
		\register_setting( 'imageshop_settings', 'imageshop_api_key' );
		\register_setting( 'imageshop_settings', 'imageshop_upload_interface' );
		\register_setting( 'imageshop_settings', 'imageshop_disable_srcset' );
	}

	/**
	 * Register settings page.
	 */
	public function register_setting_page() {
		include_once( IMAGESHOP_ABSPATH . '/admin/settings-page.php' );
	}

	/**
	 * Register menu.
	 */
	public function register_menu() {
		\add_options_page(
			\esc_html__( 'Imageshop DAM', 'imageshop-dam-connector' ),
			\esc_html__( 'Imageshop DAM', 'imageshop-dam-connector' ),
			'manage_options',
			'imageshop-settings',
			array( $this, 'register_setting_page' )
		);
	}

}
