<?php
/**
 * newsletter-popup
 *
 * API for update "newsletter_popup" cookie and open newsletter popup
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
$action	= (isset($_POST['action']) && $_POST['action']) ? $_POST['action'] : '';	// set/open

$result = array(
	'status'	=> '',
	'operation'	=> ''
);

if ( ! $action )
	return;
	
switch ($action) :

	case 'set' :
		// set newsletter popup expiry to 7 days from now
		setcookie( 'newsletter_popup', 'active', time() + WEEK_IN_SECONDS );
		
		// return success
		$result['status']		= 0;
		
		break;
		
	case 'open' :
		// check for cookie and set operation accordingly
		$result['status']		= 0;
		$result['operation']	= empty( $_COOKIE['newsletter_popup'] ) ? 'popup' : 'none';
		
endswitch;

$result = json_encode($result);
echo $result;