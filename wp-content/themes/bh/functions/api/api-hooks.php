<?php
/**
 * api-hooks.php
 *
 * API - hooks
 *
 * @author		Beit Hatfutsot
 * @package		bh/functions/api
 * @version		2.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action( 'wp_ajax_opening_hours_msg', 'opening_hours_msg' );
add_action( 'wp_ajax_nopriv_opening_hours_msg', 'opening_hours_msg' );