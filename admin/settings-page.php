<?php
/**
 * Settings page.
 */

declare( strict_types = 1 );

namespace Imageshop\WordPress;

use Imageshop\WordPress\API\Imageshop;

$imageshop = Imageshop::get_instance();

$api_key           = \get_option( 'imageshop_api_key' );
$default_interface = \get_option( 'imageshop_upload_interface' );
$disable_srcset    = \get_option( 'imageshop_disable_srcset' );

?>

<h1 class="idc-block idc-text-xl idc-mb-4">
	<?php esc_html_e( 'Imageshop settings', 'imageshop-dam-connector' ); ?>
</h1>

<div class="idc-mb-4">
	<p class="idc-mb-2">
		The Imageshop plugin will automatically replace your Media Library with the Imageshop media bank, giving you direct access to your organizations entire media portfolio.
	</p>

	<p>
		To make use of the Imageshop services, you will need to register for an account. <a href="https://www.imageshop.no/" class="idc-underline">Create a new Imageshop account, or view your account details.</a>
	</p>
</div>

<h2 class="idc-text-lg idc-mb-4">
	<?php esc_html_e( 'Connection settings', 'imageshop-dam-connector' ); ?>
</h2>

<div class="idc-flex idc-flex-col md:idc-flex-row idc-space-y-4 md:idc-space-y-0 md:idc-space-x-4 idc-mb-4">
	<div class="idc-rounded-md idc-bg-white idc-p-4 idc-w-full md:idc-w-1/3">
		<span class="idc-block idc-text-center idc-text-2xl idc-mb-2">
			✅
		</span>

		<h3 class="idc-block idc-text-center idc-text-xl idc-mb-4 idc-font-bold">
			Connected
		</h3>

		<ul class="">
			<li>✔ Feature enabled</li>
			<li>✔ Feature also enabled</li>
			<li>✔ This feature is amazing</li>
		</ul>
	</div>

	<div class="idc-rounded-md idc-bg-white idc-p-4 idc-w-full md:idc-w-1/3">
		<span class="idc-block idc-text-center idc-text-2xl idc-mb-2">
			❌
		</span>

		<h3 class="idc-block idc-text-center idc-text-xl idc-mb-4 idc-font-bold">
			Not connected
		</h3>

		<ul>
			<li>❌ Feature disabled</li>
			<li>❌ Feature also unavailable</li>
			<li>❌ This feature is amazing, but missing</li>
		</ul>
	</div>
</div>

<div class="idc-flex idc-flex-col idc-w-full idc-pr-2">
	<div class="idc-rounded-md idc-bg-white idc-p-4">

		<label for="imageshop_api_key" class="idc-block idc-mb-2">
			<?php esc_html_e( 'API key', 'imageshop-dam-connector' ); ?>
		</label>
		<input type="text" id="imageshop_api_key" name="imageshop_api_key" value="<?php echo esc_attr( $api_key ); ?>" class="" />
	</div>
</div>
