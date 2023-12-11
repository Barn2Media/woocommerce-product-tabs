<?php
namespace Barn2\Plugin\WC_Product_Tabs_Free\Admin;

use Barn2\Plugin\WC_Product_Tabs_Free\Admin\Wizard\Setup_Wizard,
	Barn2\Plugin\WC_Product_Tabs_Free\Dependencies\Lib\Util,
	Barn2\Plugin\WC_Product_Tabs_Free\Dependencies\Lib\Plugin\Plugin,
	Barn2\Plugin\WC_Product_Tabs_Free\Dependencies\Lib\Service_Container,
	Barn2\Plugin\WC_Product_Tabs_Free\Dependencies\Lib\Registerable,
	Barn2\Plugin\WC_Product_Tabs_Free\Dependencies\Lib\Service,
	Barn2\Plugin\WC_Product_Tabs_Free\Dependencies\Lib\Admin\Plugin_Promo,
	Barn2\Plugin\WC_Product_Tabs_Free\Dependencies\Lib\Admin\Settings_API_Helper;
use Barn2\Plugin\WC_Product_Tabs_Free\Post_Type;

/**
 * Handles general admin functions.
 *
 * @package   Barn2\woocommerce-product-tabs
 * @author    Barn2 Plugins <support@barn2.com>
 * @license   GPL-3.0
 * @copyright Barn2 Media Ltd
 */
class Admin_Controller implements Registerable, Service {

	use Service_Container;

	private $plugin;
	private $settings_page;

	public function __construct( Plugin $plugin ) {
		$this->plugin = $plugin;

		$this->add_services();
	}

	public function register() {
		$this->register_services();

		// Extra links on Plugins page
		add_filter( 'plugin_action_links_' . $this->plugin->get_basename(), [ $this, 'add_settings_link' ] );
		add_filter( 'plugin_row_meta', [ $this, 'add_pro_version_link' ], 10, 2 );

		// Admin scripts
		add_action( 'admin_enqueue_scripts', [ $this, 'settings_page_scripts' ] );
	}

	/**
	 * {@inheritdoc}
	 */
	public function add_services() {
		$this->add_service( 'plugin_promo', new Plugin_Promo( $this->plugin ) );
		$this->add_service( 'settings_api', new Settings_API_Helper( $this->plugin ) );
		$this->add_service( 'settings_page', new Settings_Page( $this->plugin ) );
	}

	/**
	 * Adds a setting link on the Plugins list.
	 *
	 * @param array $links
	 * @return array
	 */
	public function add_settings_link( $links ) {
		array_unshift(
			$links,
			sprintf(
				'<a href="%1$s">%2$s</a>',
				esc_url( $this->plugin->get_settings_page_url() ),
				esc_html__( 'Settings', 'woocommerce-product-tabs' )
			)
		);
		return $links;
	}

	/**
	 * Adds a Pro version link on the Plugins list.
	 *
	 * @param array $links
	 * @param string $file
	 * @return array
	 */
	public function add_pro_version_link( $links, $file ) {
		if ( $file === $this->plugin->get_basename() ) {
			$links[] = sprintf(
				'<a href="%1$s" target="_blank"><strong>%2$s</strong></a>',
				esc_url( 'https://barn2.com/wordpress-plugins/woocommerce-product-tabs/?utm_source=settings&utm_medium=settings&utm_campaign=pluginsadmin&utm_content=wta-plugins' ),
				esc_html__( 'Pro Version', 'woocommerce-product-tabs' )
			);
		}

		return $links;
	}

	/**
	 * Enqueue the admin scripts and styles.
	 *
	 * @param string $hook
	 */
	public function settings_page_scripts( $hook ) {
		$screen = get_current_screen();

		// Main Settings Page
    // TODO: Check this condition later

		if ( 'woo_product_tab' == $screen->id ) {
			wp_enqueue_style( $this->plugin_name . '-tab', plugin_dir_url( __FILE__ ) . 'assets/css/admin/tab.css', array(), $this->version, 'all' );
		}

	}

}