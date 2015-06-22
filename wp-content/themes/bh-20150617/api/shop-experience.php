<?php
/**
 * shop-experience
 *
 * API for update "shop_experience" cookie and toggle display of experience section
 *
 * @author 		Beit Hatfutsot
 * @package 	bh/api
 * @version     1.0
 */

define('DOING_AJAX', true);
define('WP_ADMIN', false);
header('Cache-Control: no-cache, must-revalidate');

require_once('../../../../wp-load.php');

// Setup variables
$action	= (isset($_POST['action']) && $_POST['action']) ? $_POST['action'] : '';	// init/open/close

$result = array(
	'status'	=> '',
	'new_state'	=> ''
);

if ( ! $action )
	return;
	
// update "shop_experience" cookie
$state = ! empty( $_COOKIE['shop_experience'] ) ? $_COOKIE['shop_experience'] : 'active';	// active(open)/inactive(close)

if ($action == 'init') :

	// in case of empty cookie - store cookie for 1 year
	if ( empty( $_COOKIE['shop_experience'] ) )
		setcookie( 'shop_experience', $state, time() + YEAR_IN_SECONDS );
		
	// return the current state
	$result['status']		= 0;
	$result['new_state']	= $state;
	
else :

	// store cookie for 1 year
	$new_state		= ($action == 'open') ? 'active' : 'inactive';
	
	setcookie( 'shop_experience', $new_state, time() + YEAR_IN_SECONDS );
	
	// return the new state
	$result['status']		= 0;
	$result['new_state']	= $new_state;
	
endif;

$result = json_encode($result);
echo $result;