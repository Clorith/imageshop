<?php
/**
 *
 */

declare(strict_types=1);

namespace Imageshop\WordPress;

class Onboarding {
	private static $instance;

	public function __construct() {
		$onboarding_completed = get_option( 'imageshop_onboarding_completed', false );

		if ( ! $onboarding_completed ) {
			add_action( 'admin_notices', array( $this, 'onboarding_notice' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'onboarding_styles' ) );
			add_action( 'rest_api_init', array( $this, 'onboarding_rest_endpoints' ) );
		}
	}

	/**
	 *
	 * @return self
	 */
	public static function get_instance() {
		if ( ! self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function user_can_setup_plugin(): bool {
		return current_user_can( 'manage_options' );
	}

	public function onboarding_rest_endpoints() {
		register_rest_route(
			'imageshop/v1',
			'onboarding/token',
			array(
				'methods'             => \WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'rest_test_token' ),
				'permission_callback' => array( $this, 'user_can_setup_plugin' ),
			)
		);

		register_rest_route(
			'imageshop/v1',
			'onboarding/interfaces',
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'rest_get_interfaces' ),
					'permission_callback' => array( $this, 'user_can_setup_plugin' ),
				),
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'rest_set_interface' ),
					'permission_callback' => array( $this, 'user_can_setup_plugin' ),
				),
			)
		);

		register_rest_route(
			'imageshop/v1',
			'onboarding/import',
			array(
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'rest_import_media_start' ),
					'permission_callback' => array( $this, 'user_can_setup_plugin' ),
				),
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'rest_import_media_status' ),
					'permission_callback' => array( $this, 'user_can_setup_plugin' ),
				),
			)
		);

		register_rest_route(
			'imageshop/v1',
			'onboarding/completed',
			array(
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => array( $this, 'rest_completed' ),
				'permission_callback' => array( $this, 'user_can_setup_plugin' ),
			)
		);
	}

	function rest_completed() {
		update_option( 'imageshop_onboarding_completed', true );

		return new \WP_REST_Response( true, 200 );
	}

	function rest_import_media_status() {
		$sync = Sync::get_instance();

		return new \WP_REST_Response(
			$sync->get_media_import_status(),
			200
		);
	}

	function rest_import_media_start() {
		$sync = Sync::get_instance();

		return $sync->sync_remote();
	}

	public function rest_test_token( \WP_REST_Request $request ) {
		$token = $request->get_param( 'token' );

		$imageshop = new REST_Controller( $token );

		if ( $imageshop->test_valid_token() ) {
			update_option( 'imageshop_api_key', $token );

			return new \WP_REST_Response(
				array(
					'valid'   => true,
					'message' => 'The token is valid',
				),
				200
			);
		}

		return new \WP_REST_Response(
			array(
				'valid'   => false,
				'message' => 'The token is not valid, or no user found',
			),
			400
		);
	}

	public function rest_get_interfaces() {
		$imageshop = REST_Controller::get_instance();

		return new \WP_REST_Response(
			array(
				'interfaces' => $imageshop->get_interfaces(),
			),
			200
		);
	}

	public function rest_set_interface( \WP_REST_Request $request ) {
		$interface = $request->get_param( 'interface' );

		update_option( 'imageshop_upload_interface', $interface );
	}

	public function onboarding_styles() {
		if ( ! $this->user_can_setup_plugin() ) {
			return;
		}

		$asset = require_once IMAGESHOP_ABSPATH . '/build/onboarding.asset.php';

		wp_enqueue_style( 'imageshop-onboarding', plugins_url( 'build/onboarding.css', IMAGESHOP_PLUGIN_BASE_NAME ), array(), $asset['version'] );
		wp_enqueue_script(
			'imageshop-onboarding',
			plugins_url( 'build/onboarding.js', IMAGESHOP_PLUGIN_BASE_NAME ),
			$asset['dependencies'],
			$asset['version'],
			true
		);
	}

	public function onboarding_notice() {
		if ( ! $this->user_can_setup_plugin() ) {
			return;
		}
		?>

		<div id="imageshop-onboarding"></div>

		<?php
	}
}
