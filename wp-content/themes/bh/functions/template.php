<?php
/**
 * Template functions
 *
 * @author 		Beit Hatfutsot
 * @package 	bh/functions
 * @version     2.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * BH_newsletter_popup
 *
 * @return		String		newsletter button and popup
 */
function BH_newsletter_popup() {
	$output = '';

	if ( is_active_sidebar('newsletter-top-menu') ) :

		$output .= '<div class="newsletter-popup-btn">';
			$output .= '<button class="label">' . __('ENews', 'BH') . '</button>';
		$output .= '</div>';

		$output .= '<div class="newsletter-popup-content">';
			$output .= BH_get_dynamic_sidebar('newsletter-top-menu');
			$output .= '<span class="glyphicon glyphicon-remove"></span>';
		$output .= '</div>';

	endif;

	return $output;
}

/**
 * BH_header_links_n_icons
 * 
 * Display icons and links as part of header elements
 * 
 * @return		String
 */
function BH_header_links_n_icons() {
	ob_start();
	
	get_template_part('views/header/header-elements');
	$output = ob_get_contents();
	
	ob_end_clean();
	
	return $output;
}

/**
 * BH_get_contact_details
 * 
 * get homepage contact details section
 * 
 * @return		String
 */
function BH_get_contact_details() {
	ob_start();
	
	get_template_part('views/main/contact-details/contact-details');
	$output = ob_get_contents();
	
	ob_end_clean();
	
	return $output;
}