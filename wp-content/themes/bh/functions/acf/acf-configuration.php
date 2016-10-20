<?php
/**
 * ACF configuration
 *
 * @author 		Beit Hatfutsot
 * @package 	bh/functions/acf
 * @version     2.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Create options sub pages
 */

if ( function_exists('acf_add_options_sub_page') ) {

	acf_add_options_sub_page('Header/Footer');
	acf_add_options_sub_page('Contact Details');
	acf_add_options_sub_page('Main Banner');
	acf_add_options_sub_page('General');
	acf_add_options_sub_page('Shop');

}

/**********************************/
/* transient support action hooks */
/**********************************/

if ( function_exists('BH_acf_save_options') ) {
	add_action('acf/save_post', 'BH_acf_save_options', 20);
}