<?php

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
	define( 'TEMPLATE',		get_bloginfo('template_directory') );
	define( 'HOME',			home_url( '/' ) );
	define( 'CSS_DIR',		TEMPLATE . '/css' );
	define( 'JS_DIR',		TEMPLATE . '/js' );
	define( 'EXR_API_KEY',	'8173E30F944972AB110F61D13501D61B' );	// Exchange Rate API key
	
	// Google Fonts
	/*
	$google_fonts = array (
		'Asap'	=> 'https://fonts.googleapis.com/css?family=Asap:400,700,400italic,700italic'
	);
	*/

?>