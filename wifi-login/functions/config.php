<?php
/**
 * Configuration
 *
 * @author		Beit Hatfutsot
 * @package		wifi-login/functions
 * @since		1.0.0
 * @version		1.0.0
 */

/**
 * Constants
 */
define( 'ABSPATH',	dirname( __DIR__ ) );
define( 'TEMPLATE',	dirname( __DIR__ ) );

/**
 * Globals
 */
global $globals;
$globals = array(
	'google_fonts'	=> array(),		// Google Fonts
);

/**
 * Google Fonts
 */
$globals[ 'google_fonts' ] = array(
	'Open Sans'			=> '//fonts.googleapis.com/css?family=Open+Sans:400,400italic,600,700'
);