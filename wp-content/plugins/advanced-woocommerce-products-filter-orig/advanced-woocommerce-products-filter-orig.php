<?php
/**
 * Plugin Name: Advanced WooCommerce Products Filter Orig
 * Plugin URI: http://www.htmline.com/
 * Description: WooCommerce widget for products filter
 * Version: 1.0 
 * Author: Nir Goldberg 
 * Author URI: http://www.htmline.com/
 * License: GPLv2+
 * Text Domain: awpf
 * Domain Path: /lang
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists('awpf') ) :

class awpf {

	// vars
	var $settings;

	/**
	 * __construct
	 *
	 * Constructor
	 *
	 * @param	n/a
	 * @return	n/a
	 */
	function __construct() {

		private $settings = array(
			// basic
			'name'				=> __('Advanced WooCommerce Products Filter', 'awpf'),
			'version'			=> '1.0',

			// urls
			'basename'			=> plugin_basename( __FILE__ ),
			'path'				=> plugin_dir_path( __FILE__ ),
			'dir'				=> plugin_dir_url( __FILE__ )
		);

		add_action( 'admin_menu', array( $this, 'awpf_add_menu' ) );
		register_activation_hook( __FILE__, array( $this, 'awpf_install' ) );
		register_deactivation_hook( __FILE__, array( $this, 'awpf_uninstall' ) );

	}

	/**
	 * awpf_add_menu
	 *
	 * Actions perform at loading of admin menu
	 */
	function awpf_add_menu() {

		add_menu_page( __('Advanced WooCommerce Products Filter', 'awpf'), __('AWPF', 'awpf'), 'manage_options', 'awpf-dashboard', array(
				__CLASS__,
				'wpa_page_file_path'
			), plugins_url('images/awpf-logo.png', __FILE__), '2.2.9' );

		// Dashboard
		add_submenu_page( 'awpf-dashboard', __('AWPF Dashboard', 'awpf'), __(' Dashboard', 'awpf'), 'manage_options', 'awpf-dashboard', array(
				__CLASS__,
				'wpa_page_file_path'
			));

		// Settings
		add_submenu_page( 'awpf-dashboard', __('AWPF Settings', 'awpf'), '<b style="color:#f9845b">' . __('Settings', 'awpf') . '</b>', 'manage_options', 'awpf-settings', array(
				__CLASS__,
				'wpa_page_file_path'
			));
	}

    /*
     * Actions perform on loading of menu pages
     */
    function wpa_page_file_path() {



    }

    /*
     * Actions perform on activation of plugin
     */
    function wpa_install() {



    }

    /*
     * Actions perform on de-activation of plugin
     */
    function wpa_uninstall() {



    }

}

endif; // class_exists check

new awpf();


/**
 * Check if WooCommerce is active
 */
if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option('active_plugins') ) ) )
	return;

define( 'ADVANCED_WOOCOMMERCE_PRODUCTS_FILTER_VERSION', '1.0' );

if ( ! defined( 'ADVANCED_WOOCOMMERCE_PRODUCTS_FILTER_PLUGIN_BASENAME' ) )
	define( 'ADVANCED_WOOCOMMERCE_PRODUCTS_FILTER_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

if ( ! defined( 'ADVANCED_WOOCOMMERCE_PRODUCTS_FILTER_PLUGIN_NAME' ) )
	define( 'ADVANCED_WOOCOMMERCE_PRODUCTS_FILTER_PLUGIN_NAME', trim( dirname( ADVANCED_WOOCOMMERCE_PRODUCTS_FILTER_PLUGIN_BASENAME ), '/' ) );

if ( ! defined( 'ADVANCED_WOOCOMMERCE_PRODUCTS_FILTER_PLUGIN_DIR' ) )
	define( 'ADVANCED_WOOCOMMERCE_PRODUCTS_FILTER_PLUGIN_DIR', untrailingslashit( dirname( __FILE__ ) ) );

if ( ! defined( 'ADVANCED_WOOCOMMERCE_PRODUCTS_FILTER_PLUGIN_INCLUDES_DIR' ) )
	define( 'ADVANCED_WOOCOMMERCE_PRODUCTS_FILTER_PLUGIN_INCLUDES_DIR', ADVANCED_WOOCOMMERCE_PRODUCTS_FILTER_PLUGIN_DIR . '/includes' );

if ( ! defined( 'ADVANCED_WOOCOMMERCE_PRODUCTS_FILTER_PLUGIN_WIDGETS_DIR' ) )
	define( 'ADVANCED_WOOCOMMERCE_PRODUCTS_FILTER_PLUGIN_WIDGETS_DIR', ADVANCED_WOOCOMMERCE_PRODUCTS_FILTER_PLUGIN_DIR . '/widgets' );

if ( ! defined( 'ADVANCED_WOOCOMMERCE_PRODUCTS_FILTER_PLUGIN_URL' ) )
	define( 'ADVANCED_WOOCOMMERCE_PRODUCTS_FILTER_PLUGIN_URL', untrailingslashit( plugins_url( '', __FILE__ ) ) );

if ( ! defined( 'ADVANCED_WOOCOMMERCE_PRODUCTS_FILTER_PLUGIN_IMAGES_DIR' ) )
	define( 'ADVANCED_WOOCOMMERCE_PRODUCTS_FILTER_PLUGIN_IMAGES_DIR', ADVANCED_WOOCOMMERCE_PRODUCTS_FILTER_PLUGIN_URL . '/images' );

if ( ! defined( 'ADVANCED_WOOCOMMERCE_PRODUCTS_FILTER_PLUGIN_CSS_DIR' ) )
	define( 'ADVANCED_WOOCOMMERCE_PRODUCTS_FILTER_PLUGIN_CSS_DIR', ADVANCED_WOOCOMMERCE_PRODUCTS_FILTER_PLUGIN_URL . '/css' );

if ( ! defined( 'ADVANCED_WOOCOMMERCE_PRODUCTS_FILTER_PLUGIN_JS_DIR' ) )
	define( 'ADVANCED_WOOCOMMERCE_PRODUCTS_FILTER_PLUGIN_JS_DIR', ADVANCED_WOOCOMMERCE_PRODUCTS_FILTER_PLUGIN_URL . '/js' );

/****************************************************************************************************************************************************/
/* scripts & styles
/****************************************************************************************************************************************************/

/**
 * awpf_plugin_backend_enqueue
 *
 * Register/enqueue backend scripts and styles
 *
 * @param	string		$hook		admin page
 */
function awpf_plugin_backend_enqueue($hook) {
	if( 'widgets.php' != $hook )
		return;
	
	// CSS
	wp_register_style( ADVANCED_WOOCOMMERCE_PRODUCTS_FILTER_PLUGIN_NAME . '-admin-style',		ADVANCED_WOOCOMMERCE_PRODUCTS_FILTER_PLUGIN_CSS_DIR . '/admin-style.css',	array(),			ADVANCED_WOOCOMMERCE_PRODUCTS_FILTER_VERSION );
}
add_action( 'admin_enqueue_scripts', 'awpf_plugin_backend_enqueue' );

/**
 * awpf_plugin_frontend_enqueue
 *
 * Register/enqueue frontend scripts and styles
 */
function awpf_plugin_frontend_enqueue() {
	if( is_admin() )
		return;
	
	// CSS
	wp_register_style( 'jquery-ui',																ADVANCED_WOOCOMMERCE_PRODUCTS_FILTER_PLUGIN_CSS_DIR . '/jquery-ui.min.css',	array(),			ADVANCED_WOOCOMMERCE_PRODUCTS_FILTER_VERSION );

	// JS
	wp_register_script( 'jquery-ui',															ADVANCED_WOOCOMMERCE_PRODUCTS_FILTER_PLUGIN_JS_DIR . '/jquery-ui.min.js',	array('jquery'),	ADVANCED_WOOCOMMERCE_PRODUCTS_FILTER_VERSION,	true );
	wp_register_script( ADVANCED_WOOCOMMERCE_PRODUCTS_FILTER_PLUGIN_NAME . '-products-filter',	ADVANCED_WOOCOMMERCE_PRODUCTS_FILTER_PLUGIN_JS_DIR . '/products-filter.js',	array('jquery'),	ADVANCED_WOOCOMMERCE_PRODUCTS_FILTER_VERSION,	true );
}
add_action( 'wp_enqueue_scripts', 'awpf_plugin_frontend_enqueue' );

/****************************************************************************************************************************************************/
/* Styling widgets admin area
/****************************************************************************************************************************************************/

/**
 * awpf_widgets_style
 *
 * Modify widget appearance style
 */
function awpf_widgets_style() {
echo <<<EOF
	<style type="text/css">
		div.widget[id*=advanced_woocommerce_products_filter] .widget-title h3 {
			color: #2191bf;
		}
	</style>
EOF;
}
add_action( 'admin_print_styles-widgets.php', 'awpf_widgets_style' );

/****************************************************************************************************************************************************/
/* Includes
/****************************************************************************************************************************************************/

require_once ADVANCED_WOOCOMMERCE_PRODUCTS_FILTER_PLUGIN_INCLUDES_DIR . '/functions.php';

/****************************************************************************************************************************************************/
/* Include widgets
/****************************************************************************************************************************************************/

require_once ADVANCED_WOOCOMMERCE_PRODUCTS_FILTER_PLUGIN_WIDGETS_DIR . '/advanced-woocommerce-products-filter.php';