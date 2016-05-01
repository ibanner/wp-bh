<?php
/**
 * pray.php
 * 
 * Display pray HTML
 *
 * @author		Beit Hatfutsot
 * @package		prayer
 * @version		1.0
 */

// get GET variables
$pray_id	= (isset($_GET['pray_id'])	&& $_GET['pray_id'])	? $_GET['pray_id'] :	'';
$lang		= (isset($_GET['lang'])		&& $_GET['lang'])		? $_GET['lang'] :		'';

if ( ! $pray_id )
	// die
	die();

// include functions
require_once('functions.php');

// xml url
$url	= 'Prayer.xml';

// get pray info
$pray	= get_pray($url, $pray_id);

if ( ! $pray )
	// die
	die();

// pray header
include('includes/pray-header.php');

// pray body
include('includes/pray-body.php');

// pray footer
include('includes/pray-footer.html');