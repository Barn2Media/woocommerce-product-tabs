<?php
/**
 * Plugin Name: WooCommerce Product Tabs (Free)
 * Plugin URI: https://barn2.com/wordpress-plugins/woocommerce-product-tabs/
 * Description: Boost your product pages by adding custom tabs containing extra information. Add an unlimited number of extra tabs for all products or specific categories.
 * Version: 2.0.24
 * Author: Barn2 Plugins
 * Author URI: https://barn2.com
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: woocommerce-product-tabs
 * Domain Path: /languages
 * Requires PHP: 7.4
 * Requires at least: 6.0
 * Tested up to: 6.4
 * WC requires at least: 6.5
 * WC tested up to: 8.2.2
 *
 * @package Woocommerce_Product_Tabs
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Check if WooCommerce is active.
 */
require_once ABSPATH . '/wp-admin/includes/plugin.php';

if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) && ! function_exists( 'WC' ) ) {
  return;
}

// Bail if the WTA Pro is active
if( is_plugin_active( 'woocommerce-product-tabs-pro/woocommerce-product-tabs.php' ) ) {
	return;
}

add_action( 'before_woocommerce_init', function() {
	if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
} );

// Define.
define( 'WOOCOMMERCE_PRODUCT_TABS_NAME', 'Woocommerce Product Tabs' );
define( 'WOOCOMMERCE_PRODUCT_TABS_SLUG', 'woocommerce-product-tabs' );
define( 'WOOCOMMERCE_PRODUCT_TABS_VERSION', '2.0.23' );
define( 'WOOCOMMERCE_PRODUCT_TABS_BASENAME', basename( dirname( __FILE__ ) ) );
define( 'WOOCOMMERCE_PRODUCT_TABS_DIR', rtrim( plugin_dir_path( __FILE__ ), '/' ) );
define( 'WOOCOMMERCE_PRODUCT_TABS_URL', rtrim( plugin_dir_url( __FILE__ ), '/' ) );
define( 'WOOCOMMERCE_PRODUCT_TABS_POST_TYPE_TAB', 'woo_product_tab' );
define( 'WPT_UPGRADE_URL', 'https://barn2.com/wordpress-plugins/woocommerce-product-tabs/#pricing' );

if ( ! defined( 'WP_WELCOME_DIR' ) ) {
	define( 'WP_WELCOME_DIR', WOOCOMMERCE_PRODUCT_TABS_DIR . '/vendor/ernilambar/wp-welcome' );
}

if ( ! defined( 'WP_WELCOME_URL' ) ) {
	define( 'WP_WELCOME_URL', WOOCOMMERCE_PRODUCT_TABS_URL . '/vendor/ernilambar/wp-welcome' );
}

// Include autoload.
if ( file_exists( WOOCOMMERCE_PRODUCT_TABS_DIR . '/vendor/autoload.php' ) ) {
	require_once WOOCOMMERCE_PRODUCT_TABS_DIR . '/vendor/autoload.php';
	require_once WOOCOMMERCE_PRODUCT_TABS_DIR . '/vendor/ernilambar/wp-welcome/init.php';
}

/**
 * The code that runs during plugin activation.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-product-tabs-activator.php';

/**
 * The code that runs during plugin deactivation.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-product-tabs-deactivator.php';

/** This action is documented in includes/class-woocommerce-product-tabs-activator.php */
register_activation_hook( __FILE__, array( 'Woocommerce_Product_Tabs_Activator', 'activate' ) );

/** This action is documented in includes/class-woocommerce-product-tabs-deactivator.php */
register_deactivation_hook( __FILE__, array( 'Woocommerce_Product_Tabs_Deactivator', 'deactivate' ) );

/**
 * The core plugin class that is used to define internationalization,
 * dashboard-specific hooks, and public-facing site hooks.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-product-tabs.php';

// Load admin page.
require_once plugin_dir_path( __FILE__ ) . 'admin/admin.php';

/**
 * Begins execution of the plugin.
 *
 * @since 1.0.0
 */
function run_woocommerce_product_tabs() {
	$plugin = new Woocommerce_Product_Tabs();
	$plugin->run();
}

run_woocommerce_product_tabs();
