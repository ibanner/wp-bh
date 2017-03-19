<?php
/**
 * WooCommerce Google Product Feed filters and hooks
 *
 * @author 		Beit Hatfutsot
 * @package 	bh/functions
 * @version     2.0
 */

/**
 * BH_gpf_exclude_he_product
 * 
 * Exclude Hebrew translated products
 * 
 * @param	boolean		$excluded		true/false
 * @param	number		$product_id		product ID
 * @param	string		$feed_format	feed format
 * @return	boolean						true/false
 */
function BH_gpf_exclude_he_product($excluded, $product_id, $feed_format) {
	if ( ! $excluded && function_exists('icl_object_id') ) {
		$product_he_id = icl_object_id($product_id, 'product', false, 'he');
		if ($product_he_id && $product_he_id == $product_id)
			$excluded = true;
	}

	return $excluded;
}
add_filter( 'woocommerce_gpf_exclude_product', 'BH_gpf_exclude_he_product', 10, 3 );

/**
 * BH_gpf_set_description
 * 
 * Set Product Short Description as the feed item description
 * 
 * @param	string		$description	product description (originally taken from product content)
 * @param	number		$product_id		product ID
 * @return	string						product excerpt
 */
function BH_gpf_set_description($description, $product_id) {
	global $post;

	$save_post = $post;
	$post = get_post( $product_id );
	setup_postdata( $post );
	
	$output = strip_tags( get_the_excerpt(), '<p>' );
	$output .= '. ';
	$output .= strip_tags( $post->post_content, '<p>' );
	
	$post = $save_post;
	
	return $output;
}
add_filter( 'woocommerce_gpf_description', 'BH_gpf_set_description', 10, 2 );

/**
 * BH_gpf_set_store_info
 * 
 * Modify Google Product Feed store info
 * 
 * @param	object		$store_info		store info object
 * @return	object						modified store info object
 */
function BH_gpf_set_store_info($store_info) {
	$store_info->currency = 'USD';

	return $store_info;
}
add_filter( 'woocommerce_gpf_store_info', 'BH_gpf_set_store_info', 10, 1 );

/**
 * BH_gpf_set_id
 * 
 * Set product ID
 * 
 * @param	array		$elements		feed item elements
 * @return	array						modified feed item elements
 */
function BH_gpf_set_id($elements, $product_id) {
	$elements['id'] = array($product_id);

	return $elements;
}
add_filter( 'woocommerce_gpf_elements', 'BH_gpf_set_id', 11, 2 );

/**
 * BH_gpf_set_availability
 * 
 * Set product availability
 * 
 * @param	array		$elements		feed item elements
 * @return	array						modified feed item elements
 */
function BH_gpf_set_availability($elements, $product_id) {
	$product		= get_product($product_id);
	$availability	= $product->is_in_stock() ? 'in stock' : 'out of stock';

	$elements['availability'] = array($availability);

	return $elements;
}
add_filter( 'woocommerce_gpf_elements', 'BH_gpf_set_availability', 11, 2 );

/**
 * BH_gpf_set_brand
 * 
 * Set product artists as the feed item brand
 * 
 * @param	array		$elements		feed item elements
 * @return	array						modified feed item elements
 */
function BH_gpf_set_brand($elements, $product_id) {
	$artists = wp_get_post_terms($product_id, 'artist');

	reset($artists);
	$artist = current($artists);

	if ($artist) {
		$elements['brand'] = array($artist->name);
	}

	return $elements;
}
add_filter( 'woocommerce_gpf_elements', 'BH_gpf_set_brand', 11, 2 );

/**
 * BH_gpf_set_mpn_n_gtin
 * 
 * Set sku as the feed item MPN and GTIN
 * 
 * @param	array		$elements		feed item elements
 * @return	array						modified feed item elements
 */
function BH_gpf_set_mpn_n_gtin($elements, $product_id) {
	$product = wc_get_product($product_id);

	if ( ! $product )
		return $elements;

	$sku = $product->get_sku();
	if ( empty($sku) )
		return $elements;

	$elements['mpn']	= array($sku);
	$elements['gtin']	= array( gtin_12($sku) );

	return $elements;
}
add_filter( 'woocommerce_gpf_elements', 'BH_gpf_set_mpn_n_gtin', 11, 2 );

/**
 * BH_gpf_set_shipping_dimensions
 * 
 * Set product shipping dimensions for empty values
 * 
 * @param	array		$elements		feed item elements
 * @return	array						modified feed item elements
 */
function BH_gpf_set_shipping_dimensions($elements, $product_id) {
	$elements['shipping_length']	= $elements['shipping_length']	? $elements['shipping_length']	: array('1 in');
	$elements['shipping_width']		= $elements['shipping_width']	? $elements['shipping_width']	: array('1 in');
	$elements['shipping_height']	= $elements['shipping_height']	? $elements['shipping_height']	: array('1 in');

	return $elements;
}
add_filter( 'woocommerce_gpf_elements', 'BH_gpf_set_shipping_dimensions', 11, 2 );

/**
 * BH_gpf_set_shipping_weight
 * 
 * Set product shipping weight for empty value
 * 
 * @param	string		$product_weight		product weight
 * @param	number		$product_id			product ID
 * @return	string							product weight or '0 g' if empty
 */
function BH_gpf_set_shipping_weight($product_weight, $product_id) {
	if ( $product_weight == '' )
		$product_weight = '1';

	return $product_weight;
}
add_filter( 'woocommerce_gpf_shipping_weight', 'BH_gpf_set_shipping_weight', 10, 2 );

/**
 * gtin_12
 * 
 * Generate GTIN-12 string according to GS1 (http://www.gs1us.org/resources/tools/check-digit-calculator)
 * 
 * @param	string		$str				string of digits
 * @return	string							GTIN-12 format string or empty string in case of bad input
 */
function gtin_12($str) {
	if ( strlen($str) > 10 || ! ctype_digit($str) )
		return '';

	$output = '0' . $str;	// set '0' as leading digit in order to define a price digit
	$output = str_pad($output, 11, '0', STR_PAD_RIGHT);		// define zeros from the right in order to generate 11 digits length string

	// calculate a check digit
	$digits = str_split($output);
	$sum = 0;

	foreach ($digits as $key => $d) {
		$sum += $d * ( $key % 2 ? 1 : 3 );
	}

	$roundSum = ceil($sum / 10) * 10;

	return $output . strval($roundSum - $sum);
}