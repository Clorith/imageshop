<?php

namespace Imageshop\WordPress\REST\Endpoints\Settings;

use Imageshop\WordPress\API\Imageshop;

class TestConnection {
	/**
	 * Test the connection to the Imageshop API, and validate the API token.
	 *
	 * @return \WP_REST_Response
	 */
	public static function run( \WP_REST_Request $request ) {
		$api_key = $request->get_param( 'token' );

		$response = array(
			'message' => \sprintf(
				'<div class="notice notice-success fade"><p>%s</p></div>',
				\esc_html__( 'Connection is successfully established. Save the settings.', 'imageshop-dam-connector' )
			),
		);

		try {
			$rest_controller = new Imageshop( $api_key );
			$can_upload      = $rest_controller->can_upload();

			if ( ! $can_upload ) {
				$response['message'] = \sprintf(
					'<div class="notice notice-warning"><p>%s</p></div>',
					\esc_html__( 'Could not establish a connection to Imageshop.', 'imageshop-dam-connector' )
				);
			}
		} catch ( \Exception $e ) {
			$response['message'] = \sprintf(
				'<div class="notice notice-error"><p>%s</p></div>',
				\sprintf(
				// translators: 1: Error message. 2: Error code.
					\esc_html__( 'Could not establish a connection: %1$s (%2$d)', 'imageshop-dam-connector' ),
					$e->getMessage(),
					$e->getCode()
				)
			);
		}

		return new \WP_REST_Response( $response );
	}
}
