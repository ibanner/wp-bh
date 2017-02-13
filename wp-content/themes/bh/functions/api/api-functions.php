<?php
/**
 * api-functions.php
 *
 * API - functions
 *
 * @author		Beit Hatfutsot
 * @package		bh/functions/api
 * @version		2.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * opening_hours_msg
 *
 * This function returns an opening hours msg as recorded in BH-opening-hours-msg transient
 *
 * @since		2.0
 * @param		N/A
 * @return		N/A
 */
function opening_hours_msg() {

	// get data
	$wpml_lang	= ( isset($_POST['wpml_lang']) && $_POST['wpml_lang'] ) ? $_POST['wpml_lang'] : '';

	// get transient
	$transient_key = 'BH-opening-hours-msg' . ( $wpml_lang ? '-' . $wpml_lang : '' );
	$msg = get_transient( $transient_key );

	// initiate returned array
	$result = array();

	// status
	$result['status'] = $msg !== false ? '0' : '1' . $_POST['wpml_lang'];

	// msg
	if ($msg !== false) {
		$result['msg'] = $msg;
	}

	echo json_encode( $result );

	// die
	die();

}