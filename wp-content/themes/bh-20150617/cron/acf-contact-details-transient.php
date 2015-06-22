<?php

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
	 * set contact details transient
	 * 
	 * need to be called from www-data crontab:
	 * 5 * * * * php /data/wp-bh/wp-content/themes/bh/cron/acf-contact-details-transient.php
	 */
	if ( function_exists('BH_acf_save_options') )
		BH_acf_save_options('options');

?>