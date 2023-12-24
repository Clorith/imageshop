<?php

namespace Imageshop\WordPress\REST;

class Controller extends \WP_REST_Controller {

	protected $namespace = 'imageshop/v1';

	/**
	 * Class constructor.
	 */
	public function __construct() {
		\add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	/**
	 * Register WordPress REST API endpoints.
	 *
	 * @return void
	 */
	public function register_routes() {
		\register_rest_route(
			$this->namespace,
			'/settings/test-connection',
			array(
				'methods'             => \WP_REST_Server::CREATABLE,
				'args'                => array(
					'token' => array(
						'type'     => 'string',
						'required' => true,
					),
				),
				'callback'            => __NAMESPACE__ . '\\Endpoints\\Settings\\TestConnection' . '::run',
				'permission_callback' => function() {
					return true;
					return \current_user_can( 'manage_options' );
				},
			)
		);
	}

}
