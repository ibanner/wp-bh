<?php
/**
 * Plugin Name: Advanced WooCommerce Products Filter
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

// check if WooCommerce is active
if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option('active_plugins') ) ) )
	return;

if ( ! class_exists('awpf') ) :

class awpf {

	var $settings;

	/**
	 * __construct
	 *
	 * A dummy constructor to ensure AWPF is only initialized once
	 *
	 * @since		1.0
	 * @param		N/A
	 * @return		N/A
	 */
	function __construct() {

		/* Do nothing here */

	}

	/**
	 * initialize
	 *
	 * The real constructor to initialize AWPF
	 *
	 * @since		1.0
	 * @param		N/A
	 * @return		N/A
	 */
	function initialize() {

		$this->settings = array(
			// basic
			'name'			=> __('Advanced WooCommerce Products Filter', 'awpf'),
			'version'		=> '1.0',

			// urls
			'basename'		=> plugin_basename( __FILE__ ),
			'path'			=> plugin_dir_path( __FILE__ ),		// with trailing slash
			'dir'			=> plugin_dir_url( __FILE__ ),		// with trailing slash

			// options
			'show_admin'	=> true,
			'widget_style'	=> true,
			'capability'	=> 'manage_options'
		);

		// include helpers
		include_once('api/api-helpers.php');

		// admin
		if( is_admin() ) {
			
			awpf_include('admin/admin.php');
			awpf_include('admin/dashboard.php');
			awpf_include('admin/settings.php');
			awpf_include('admin/widgets.php');

		}

		// functions
		awpf_include('includes/awpf-hooks.php');
		awpf_include('includes/awpf-functions.php');

		// widgets
		awpf_include('widgets/awpf-widget.php');

		// actions
		add_action( 'init',	array($this, 'init'), 5 );
		add_action( 'init',	array($this, 'register_assets'), 5 );

		// plugin activation / deactivation
		register_activation_hook( __FILE__,		array( $this, 'awpf_install' ) );
		register_deactivation_hook( __FILE__,	array( $this, 'awpf_uninstall' ) );

	}

	/**
	 * init
	 *
	 * This function will run after all plugins and theme functions have been included
	 *
	 * @since		1.0
	 * @param		N/A
	 * @return		N/A
	 */
	function init() {

		// exit if called too early
		if ( ! did_action('plugins_loaded') )
			return;

		// exit if already init
		if( awpf_get_setting('init') )
			return;

		// only run once
		awpf_update_setting('init', true);

		// redeclare dir - allow another plugin to modify dir
		awpf_update_setting( 'dir', plugin_dir_url( __FILE__ ) );

		// set text domain
		load_textdomain( 'awpf', awpf_get_path( 'lang/awpf-' . get_locale() . '.mo' ) );

		// action for 3rd party
		do_action('awpf/init');

	}

	/**
	 * register_assets
	 *
	 * This function will register scripts and styles
	 *
	 * @since		1.0
	 * @param		N/A
	 * @return		N/A
	 */
	function register_assets() {

		// vars
		$version	= awpf_get_setting('version');
		$lang		= get_locale();
		$scripts	= array();
		$styles		= array();

		// append scripts
		$scripts['jquery-ui'] = array(
			'src'	=> awpf_get_dir('js/jquery-ui.min.js'),
			'deps'	=> array('jquery')
		);

		$scripts['awpf-products-filter'] = array(
			'src'	=> awpf_get_dir('js/awpf-products-filter.js'),
			'deps'	=> array('jquery')
		);

		// register scripts
		foreach ( $scripts as $handle => $script ) {

			wp_register_script( $handle, $script['src'], $script['deps'], $version );

		}

		// append styles
		$styles['jquery-ui'] = array(
			'src'	=> awpf_get_dir('css/jquery-ui.min.css'),
			'deps'	=> false
		);

		$styles['awpf-admin-style'] = array(
			'src'	=> awpf_get_dir('css/awpf-admin-style.css'),
			'deps'	=> false
		);

		// register styles
		foreach( $styles as $handle => $style ) {

			wp_register_style( $handle, $style['src'], $style['deps'], $version );

		}

	}

	/**
	 * awpf_install
	 *
	 * Actions perform on activation of plugin
	 */
	function awpf_install() {}

	/**
	 * awpf_uninstall
	 *
	 * Actions perform on deactivation of plugin
	 */
	function awpf_uninstall() {}

}

/**
 * awpf
 *
 * The main function responsible for returning the one true awpf Instance
 *
 * @since	1.0
 * @param	N/A
 * @return	(object)
 */
function awpf() {

	global $awpf;

	if( ! isset($awpf) ) {

		$awpf = new awpf();

		$awpf->initialize();

	}

	return $awpf;

}

// initialize
awpf();

endif; // class_exists check