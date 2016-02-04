<?php
/**
 * AWPF - helper functions
 *
 * @author		Nir Goldberg
 * @package		api
 * @version		1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * awpf_get_setting
 *
 * This function will return a value from the settings array found in the awpf object
 *
 * @since		1.0
 * @param		$name (string) the setting name to return
 * @return		(mixed)
 */
function awpf_get_setting( $name, $default = null ) {

	// vars
	$settings = awpf()->settings;

	// find setting
	$setting = awpf_maybe_get( $settings, $name, $default );

	// filter for 3rd party customization
	$setting = apply_filters( "awpf/settings/{$name}", $setting );

	// return
	return $setting;

}

/**
 * awpf_update_setting
 *
 * This function will update a value into the settings array found in the awpf object
 *
 * @since		1.0
 * @param		$name (string) the setting name to update
 * @param		$value (mixed) the setting value to update
 * @return		N/A
 */
function awpf_update_setting( $name, $value ) {

	awpf()->settings[ $name ] = $value;

}

/**
 * awpf_get_path
 *
 * This function will return the path to a file within the AWPF plugin folder
 *
 * @since		1.0
 * @param		$path (string) the relative path from the root of the AWPF plugin folder
 * @return		(string)
 */
function awpf_get_path( $path ) {

	return awpf_get_setting('path') . $path;

}

/**
 * awpf_get_dir
 *
 * This function will return the url to a file within the AWPF plugin folder
 *
 * @since		1.0
 * @param		$path (string) the relative path from the root of the AWPF plugin folder
 * @return		(string)
 */
function awpf_get_dir( $path ) {

	return awpf_get_setting('dir') . $path;

}

/**
 * awpf_include
 *
 * This function will include a file
 *
 * @since		1.0
 * @param		$file (string) the file name to be included
 * @return		N/A
 */
function awpf_include( $file ) {

	$path = awpf_get_path( $file );

	if( file_exists($path) ) {

		include_once( $path );

	}

}

/**
 * awpf_get_view
 *
 * This function will load in a file from the 'admin/views' folder and allow variables to be passed through
 *
 * @since		1.0
 * @param		$view_name (string)
 * @param		$args (array)
 * @return		N/A
 */
function awpf_get_view( $view_name = '', $args = array() ) {

	// vars
	$path = awpf_get_path("admin/views/{$view_name}.php");

	if( file_exists($path) ) {

		include( $path );

	}

}

/**
 * awpf_maybe_get
 *
 * This function will return a variable if it exists in an array
 *
 * @since		1.0
 * @param		$array (array) the array to look within
 * @param		$key (key) the array key to look for
 * @param		$default (mixed) the value returned if not found
 * @return		(mixed)
 */
function awpf_maybe_get( $array, $key, $default = null ) {

	// return default if does not exist
	if( ! isset( $array[ $key ] ) ) {

		return $default;

	}

	// return
	return $array[ $key ];

}