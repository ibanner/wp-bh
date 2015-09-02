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
 * BH_shop_cart_popup
 *
 * Get Shop Mini Cart button and popup to be displayed as part of header elements
 *
 * @param		String		$header_position		top/mid
 * @return		String								shop mini cart button and popup
 */
function BH_shop_cart_popup($header_position) {
	$output = '';

	if ( is_active_sidebar('shop-header-cart') ) :

		$output .= '<div class="shop-cart-popup-btn">';

		if ($header_position == 'top')
			$output .= '<a class="sprite-cart" href="' . WC()->cart->get_cart_url() . '"></a>';
		else
			$output .= '<button class="sprite-cart"></button>';

		// Insert shopping cart indicator placeholder - code in woocommerce.js will update this on page load
		$output .= '<div class="widget_shopping_cart_indicator"></div>';

		if ($header_position == 'mid')
			$output .= '</div>';

		$output .= '<div class="shop-cart-popup-content">';
			$output .= BH_get_dynamic_sidebar('shop-header-cart');
		$output .= '</div>';

		if ($header_position == 'top')
			$output .= '</div>';

	endif;

	return $output;
}

/**
 * BH_newsletter_popup
 *
 * Get Newsletter button and popup to be displayed as part of header elements
 *
 * @param		String		$sidebar		sidebar name
 * @return		String						newsletter button and popup or empty string in case of input failure
 */
function BH_newsletter_popup($sidebar) {
	if ( ! $sidebar )
		return '';

	$output = '';

	if ( is_active_sidebar($sidebar) ) :

		$output .= '<div class="newsletter-popup-btn">';
			$output .= '<button class="label">' . __('ENews', 'BH') . '</button>';
		$output .= '</div>';

		$output .= '<div class="newsletter-popup-content">';
			$output .= BH_get_dynamic_sidebar($sidebar);
			$output .= '<span class="glyphicon glyphicon-remove"></span>';
		$output .= '</div>';

	endif;

	return $output;
}

/**
 * BH_header_links_n_icons
 * 
 * Get icons and links to be displayed as part of header elements
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
 * Get homepage contact details section
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