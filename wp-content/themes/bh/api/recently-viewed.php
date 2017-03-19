<?php
/**
 * recently-viewed
 *
 * API for update "woocommerce_recently_viewed" cookie and display recently viewed products in a predefined placeholder
 *
 * @author 		Beit Hatfutsot
 * @package 	bh/api
 * @version     2.0
 */

define('DOING_AJAX', true);
define('WP_ADMIN', false);
header('Cache-Control: no-cache, must-revalidate');

require_once('../../../../wp-load.php');

// Setup variables
$action	= (isset($_POST['action']) && $_POST['action']) ? $_POST['action'] : '';	// show/add/remove
$postid	= (isset($_POST['postid']) && $_POST['postid']) ? $_POST['postid'] : '';	// product ID (required in case of action=add/remove)

if ( ! ($action) || ($action != 'show' && ! $postid) )
	return;
	
// update "woocommerce_recently_viewed" cookie
$viewed_products = ! empty( $_COOKIE['woocommerce_recently_viewed'] ) ? (array) explode( '|', $_COOKIE['woocommerce_recently_viewed'] ) : array();

if ($action == 'add')
	BH_add_viewed_products($postid, $viewed_products);
elseif ($action == 'remove')
	BH_remove_viewed_products($postid, $viewed_products);

if ( sizeof($viewed_products) > 0 ) :

	// there are viewed products - store cookie for 1 year
	$cookie_result = setcookie( 'woocommerce_recently_viewed', implode( '|', $viewed_products ), time() + YEAR_IN_SECONDS );
	
	if ($cookie_result) :
		// explicitly save the cookie as it won't be set until next page load
		$_COOKIE['woocommerce_recently_viewed'] = implode( '|', $viewed_products );
		
		// display recently viewed products based on the updated "woocommerce_recently_viewed" cookie
		get_template_part('views/woocommerce/recently-viewed');
	endif;
	
else :

	// there aren't viewed products - delete cookie
	$cookie_result = setcookie( 'woocommerce_recently_viewed', '', time() - HOUR_IN_SECONDS );
	
	// explicitly save the cookie as it won't be set until next page load
	$_COOKIE['woocommerce_recently_viewed'] = '';
	
endif;