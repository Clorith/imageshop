<?php

namespace Imageshop\WordPress;

class Helpers {
	public static function available_locales() {
		return array(
			'dk' => array(
				'label'     => esc_html__( 'Danish', 'imageshop-dam-connector' ),
				'default'   => false,
				'iso_codes' => array(
					'da'    => 'string',
					'dk'    => 'string',
					'da_DK' => 'string',
				),
			),
			'en' => array(
				'label'     => esc_html__( 'English', 'imageshop-dam-connector' ),
				'default'   => false,
				'iso_codes' => array(
					'/en_*/' => 'regex',
				),
			),
			'no' => array(
				'label'     => esc_html__( 'Norwegian', 'imageshop-dam-connector' ),
				'default'   => true,
				'iso_codes' => array(
					'nb'    => 'string',
					'nn'    => 'string',
					'nb_NO' => 'string',
					'nn_NO' => 'string',
				),
			),
			'sv' => array(
				'label'     => esc_html__( 'Swedish', 'imageshop-dam-connector' ),
				'default'   => false,
				'iso_codes' => array(
					'se'    => 'string',
					'sv'    => 'string',
					'sv_SE' => 'string',
				),
			),
		);
	}
}
