<?php
/**
 * opening-hours-msg.php
 *
 * cron - opening hours message
 *
 * @author		Beit Hatfutsot
 * @package		bh/cron
 * @version		2.0
 */

/**
 * initiate wordpress base path in order to call this file from cli
 */

if (php_sapi_name() !== 'cli') {
	die("Meant to be run from command line");
}

function find_wordpress_base_path() {
	$dir = dirname(__FILE__);
	do {
		if( file_exists($dir . "/wp-config.php") ) {
			return $dir;
		}
	} while( $dir = realpath("$dir/..") );
	
	return null;
}

define('BASE_PATH', find_wordpress_base_path() . "/");
define('WP_USE_THEMES', false);

global $wp, $wp_query, $wp_the_query, $wp_rewrite, $wp_did_header;
require(BASE_PATH . 'wp-load.php');

/**
 * cron job script
 *
 * Update BH-opening-hours-msg transient according to opening hours settings
 *
 * need to be called from www-data crontab
 */

// get all languages
$languages = array();

if ( function_exists('icl_get_languages') ) {
	$languages = icl_get_languages('skip_missing=0');
}

if ( $languages ) {
	foreach ( $languages as $code => $lang_data ) {
		set_opening_hours_msg($code);
	}
} else {
	set_opening_hours_msg();
}