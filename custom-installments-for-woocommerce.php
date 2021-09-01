<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://makewebbetter.com
 * @since             1.0.0
 * @package           Custom_Installments_For_Woocommerce
 *
 * @wordpress-plugin
 * Plugin Name:       Custom Installments For WooCommerce
 * Plugin URI:        https://makewebbetter.com
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            MakeWebBetter
 * Author URI:        https://makewebbetter.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       custom-installments-for-woocommerce
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
// Action for checking woocommerce plugin is activated.

add_action( 'plugins_loaded', 'check_plugin_woo_activation' );

/**
 * Callback function for checking dependency on woocommerce plugin.
 */
function check_plugin_woo_activation() {
	if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {
		add_action( 'admin_init', 'check_woocommerce_activation' );
		add_action( 'admin_notices', 'display_notices' );
	}
}

/**
 * Deactivating plugin function when woocommerce is not active.
 *
 * @return void
 */
function check_woocommerce_activation() {
	deactivate_plugins( plugin_basename( __FILE__ ) );
}

/**
 * Displaying notice function.
 *
 * @return void
 */
function display_notices() {
	if ( isset( $_GET['activate'] ) ) {
		unset( $_GET['activate'] );
	}
	?>
	<div class="error notice">
		<p>
			<strong>Warning:</strong>
			Your Current Plugin <strong>Custom installments for woocommerce </strong> won't execute
			because the <strong>Woocommerce Plugin</strong> is not active.
			Please activate the woocommerce <a href="plugins.php">plugin</a>.
		</p>
	</div>
	<?php
}


/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'CUSTOM_INSTALLMENTS_FOR_WOOCOMMERCE_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-custom-installments-for-woocommerce-activator.php
 */
function activate_custom_installments_for_woocommerce() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-custom-installments-for-woocommerce-activator.php';
	Custom_Installments_For_Woocommerce_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-custom-installments-for-woocommerce-deactivator.php
 */
function deactivate_custom_installments_for_woocommerce() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-custom-installments-for-woocommerce-deactivator.php';
	Custom_Installments_For_Woocommerce_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_custom_installments_for_woocommerce' );
register_deactivation_hook( __FILE__, 'deactivate_custom_installments_for_woocommerce' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-custom-installments-for-woocommerce.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_custom_installments_for_woocommerce() {

	$plugin = new Custom_Installments_For_Woocommerce();
	$plugin->run();

}
run_custom_installments_for_woocommerce();

add_action( 'admin_head', 'mwb_css_custom_tab' );
/**
 * Callback function for adding css in woocommerce tab.
 *
 * @return void
 */
function mwb_css_custom_tab() {
	echo '<style>
	.mwb-total__installment {
		align-items: center;
		display: flex;
		flex-wrap: wrap;
		justify-content: flex-start;
		margin: 10px;
	}

	.mwb-total__installment-title {
		flex: 0 0 100%;
		max-width: 40%;
	}

	.mwb-total__installment-title h3 {
		font-size: 18px;
	}

	.mwb-total__installment-price {
		flex: 0 0 100%;
		max-width: 60%;
	}

	.mwb-total__installment-price p {
		color: #1d2327;
		font-size: 18px;
		font-weight: 700;
	}

	.mwb_installment_price_wrapper table {
		border-collapse: collapse;
		border: 1px solid #dddddd;
		margin: 10px;
		width: calc(100% - 20px);
	}
	.mwb_installment_price_wrapper table tr {
		border-bottom: 1px solid #dddddd;
	}

	.mwb_installment_price_wrapper table td,
	.mwb_installment_price_wrapper table th {
		padding: 8px;
		text-align: left;
	}

	.mwb_installment_price_wrapper .mwb_installment_div {
		width: 40%;
	}

	.mwb_installment_price_wrapper .mwb_price_div {
		background-color: #f5f5f5;
		width: 60%;
   		color: #1d201f;
	}

	.mwb_custom_product_data p {
		font-size: 15px;
		text-align: center;
	}
	
	</style>';
}

