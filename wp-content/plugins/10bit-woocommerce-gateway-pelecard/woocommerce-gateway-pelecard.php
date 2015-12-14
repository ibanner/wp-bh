<?php
/**
 * Pelecard Payment Gateway
 * Plugin Name: woocommerce pelecard payment gateway
 * Description: Allows payment with pelecard.
 * Version: 2.0.0
 * Author: 10Bit
 * Author URI: http://www.10bit.co.il
 */
 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

include '10bit_common.php';
include 'WC_Gateway_Pelecard.php';
include '10bit_pelecard_errors_en.php';
include '10bit_pelecard_errors_he.php';
global $he_errors;
global $en_errors;
 
add_action( 'plugins_loaded', 'woocommerce_pelecard_init' );

function tenbit_gateway_pelecard__add_settings_link( $links ) {
    $settings_link = '<a href="admin.php?page=wc-settings&tab=checkout&section=wc_gateway_pelecard">'.__('Settings','TenBit_woo_pelecard').'</a>';
  	array_push( $links, $settings_link );
  	return $links;
}

$plugin = plugin_basename( __FILE__ );
add_filter( "plugin_action_links_$plugin", 'tenbit_gateway_pelecard__add_settings_link' );
?>