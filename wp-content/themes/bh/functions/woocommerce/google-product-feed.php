<?php
/**
 * WooCommerce Google Product Feed filters and hooks
 *
 * @author 		Beit Hatfutsot
 * @package 	bh/functions
 * @version     1.0
 */

/**
 * BH_gpf_exclude_he_product
 * 
 * Exclude Hebrew translated products
 * 
 * @param	boolean		$excluded		true/false
 * @param	id			$product_id		product ID
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
 * @param	id			$product_id		product ID
 * @return	string						product excerpt
 */
function BH_gpf_set_description($description, $product_id) {
	global $post;

	$save_post = $post;
	$post = get_post( $product_id );
	setup_postdata( $post );
	$excerpt = get_the_excerpt();
	$post = $save_post;
	
	return $excerpt;
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
 * BH_gpf_set_brand
 * 
 * Set product artists as the feed item brand
 * 
 * @param	array		$elements		feed item elements
 * @return	array						modified feed item elements
 */
function BH_gpf_set_brand($elements, $product_id) {
	$artists		= wp_get_post_terms($product_id, 'artist');
	$artists_items	= array();

	if ( ! empty($artists) )
		foreach ($artists as $artist)
			$artists_items[] = $artist->name;

	if ( ! empty($artists_items) )
		$elements['brand'] = $artists_items;

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
	$product = get_product($product_id);

	if ( ! $product )
		return $elements;

	$sku = $product->get_sku();
	if ( empty($sku) )
		return $elements;

	$elements['mpn'] = $elements['gtin'] = array($sku);

	return $elements;
}
add_filter( 'woocommerce_gpf_elements', 'BH_gpf_set_mpn_n_gtin', 11, 2 );