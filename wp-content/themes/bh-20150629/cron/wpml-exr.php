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
	 * Dynamically update the WooCommerce Multilangual Currency Exchange Rate
	 * 
	 * need to be called from www-data crontab
	 */
	// get current exchange rates
	
	$exurl = 'http://api.exchangeratelab.com/api/single/ILS?apikey=' . EXR_API_KEY;
	
	$ch = curl_init($exurl);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	
	$json_response_content = curl_exec($ch);
	curl_close($ch);
	
	$exchangeRatesResult = json_decode($json_response_content, true);
	
	if ( ! $exchangeRatesResult )
		return;
	
	$arr = get_option('_wcml_settings');
	$arr['currency_options']['USD']['rate'] = 1.0/$exchangeRatesResult['rate']['rate'];
	update_option('_wcml_settings', $arr);

?>