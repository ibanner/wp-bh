<?php
/**
 * Theme configuration
 *
 * @author 		Beit Hatfutsot
 * @package 	bh/functions
 * @version     2.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * theme prefix => BH
 */

// theme version is used to register styles and scripts
if ( function_exists('wp_get_theme') ) :

	$theme_data = wp_get_theme();
	$theme_version = $theme_data->get('Version');

else :

	$theme_data = get_theme_data( trailingslashit(get_stylesheet_directory()).'style.css' );
	$theme_version = $theme_data['Version'];

endif;

define( 'VERSION', $theme_version );

// other
$stylesheet = get_stylesheet();
$theme_root = get_theme_root( $stylesheet );

define( 'TEMPLATE',		get_bloginfo('template_directory') );
define( 'HOME',			home_url( '/' ) );
define( 'THEME_ROOT',	"$theme_root/$stylesheet" );
define( 'CSS_DIR',		TEMPLATE . '/css' );
define( 'JS_DIR',		TEMPLATE . '/js' );
define( 'EXR_API_KEY',	'8173E30F944972AB110F61D13501D61B' );	// Exchange Rate API key

// Google Fonts
$google_fonts = array (
	'Open Sans'			=> 'http://fonts.googleapis.com/css?family=Open+Sans:400,400italic,600,700',
	'Open Sans Hebrew'	=> 'http://fonts.googleapis.com/earlyaccess/opensanshebrew.css'
);