<?php
/**
 * Plugin Name: Bet Hatfutsot Custom Payment
 * Plugin URI: 
 * Description: Plugin to create custom payment keys and monitor payments for Beit Hatfutsot website (www.bh.org.il).
 * Version: 0.1.0
 * Author: Danny Berger
 * Author URI: 
 * License: GPL
 */

global $wpdb;

if ( ! defined( 'CUSTOM_PAYMENT_PLUGIN_DIR' ) )
	define( 'CUSTOM_PAYMENT_PLUGIN_DIR', untrailingslashit( dirname( __FILE__ ) ) );

if ( ! defined( 'CUSTOM_PAYMENT_PLUGIN_URL' ) )
	define( 'CUSTOM_PAYMENT_PLUGIN_URL', plugins_url('',  __FILE__) );

if ( ! defined( 'CUSTOM_PAYMENT_TABLE_NAME' ) )
	define('CUSTOM_PAYMENT_TABLE_NAME', $wpdb->prefix . 'bh_custom_payment');

if ( ! defined( 'CUSTOM_PAYMENT_FORM_URL' ) )
	define('CUSTOM_PAYMENT_FORM_URL', home_url(). '/custom-payment');

$capability	= 'publish_pages';

require_once('custom-payment-manager.php');

$GLOBALS['customPaymentManager'] = new CustomPaymentManager(CUSTOM_PAYMENT_TABLE_NAME);
 
register_activation_hook( __FILE__, 'create_db_table' );

function create_db_table() {
	/*
     * SOURCE: http://codex.wordpress.org/Creating_Tables_with_Plugins
     *
	 * We'll set the default character set and collation for this table.
	 * If we don't do this, some characters could end up being converted 
	 * to just ?'s when saved in our table.
	 */

	global $wpdb; 

	$charset_collate = '';

	if ( ! empty( $wpdb->charset ) ) {
		$charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
	}

	if ( ! empty( $wpdb->collate ) ) {
		$charset_collate .= " COLLATE {$wpdb->collate}";
	}

	$sql = "CREATE TABLE " . CUSTOM_PAYMENT_TABLE_NAME . " (
		paymentKey VARCHAR(12) NOT NULL,
		issuerName TINYTEXT NOT NULL,
		issuerEmail TINYTEXT NOT NULL,
		customerName TINYTEXT NOT NULL,
		customerEmail TINYTEXT NOT NULL,
		transactionKey VARCHAR(20) DEFAULT '' NOT NULL,
		total MEDIUMINT NOT NULL,
		timeIssued DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL,
		sentTo TINYTEXT NOT NULL,
		paid BOOL DEFAULT 0 NOT NULL,
		confirmation VARCHAR(20) DEFAULT '' NOT NULL,
		UNIQUE KEY (paymentKey)
		) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
}

add_action( 'admin_menu', 'custom_payment_admin_menu' );

function custom_payment_admin_menu() {
	global $capability;

	$page_title	= 'Custom Payment';
	$menu_slug	= 'bh-custom-payment-admin';
	$function	= 'admin_html';
	$icon_url	= null;
	$position	= null;

	add_menu_page($page_title, $page_title, $capability, $menu_slug, $function, $icon_url, $position);	
}

function admin_html() {
	global $wpdb, $capability;

	if ( !current_user_can( $capability ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}

	require_once('view.php');
}

add_action('admin_enqueue_scripts', 'add_scripts'); 

function add_scripts() {
	wp_register_style('bh-custom-payment', CUSTOM_PAYMENT_PLUGIN_URL . '/css/bh-custom-payment.css', array(), '1.0', false);

	wp_register_script('bh-custom-payment', CUSTOM_PAYMENT_PLUGIN_URL . '/js/bh-custom-payment.js', array('jquery'), '1.0', true);

	wp_enqueue_style('bh-custom-payment');

	wp_localize_script('bh-custom-payment','context', array('pluginUrl' => CUSTOM_PAYMENT_PLUGIN_URL));

	wp_enqueue_script('bh-custom-payment');

}

require_once('custom-payment-manager.php');
