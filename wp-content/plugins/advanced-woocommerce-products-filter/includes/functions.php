<?php
/**
 * Advanced WooCommerce Products Filter functions
 *
 * @author 		Nir Goldberg
 * @package 	advanced-woocommerce-products-filter/includes
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * AWPF_init_products_filter_values
 * 
 * 1. Initiate products filter values according to current taxonomy, term ID, price range and taxonomy terms
 * 
 * $taxonomies structure:
 * $taxonomies[ {taxonomy name} ][0]					=> taxonomy filter title
 * $taxonomies[ {taxonomy name} ][1]					=> number of products associated with this taxonomy
 * $taxonomies[ {taxonomy name} ][2][ {term ID} ][0]	=> term name
 * $taxonomies[ {taxonomy name} ][2][ {term ID} ][1]	=> number of products associated with this term
 * $taxonomies[ {taxonomy name} ][2][ {term ID} ][2]	=> whether this term is checked in taxonomy filter [1 = true / 0 = false]
 *
 * 2. Initiate $products as an array of arrays
 *
 * $products[ {product ID} ][0]							=> product price
 * $products[ {product ID} ][1]							=> whether this product is displayed according to filter state [1 = true / 0 = false]
 * $products[ {product ID} ][2][ {taxonomy name} ]		=> array of taxonomy term_id's associated with this product
 *
 * @param	string	$taxonomy			taxonomy name
 * @param	int		$taxonomy_term_id	term ID
 * @param	int		&$min_price			minimum filter price
 * @param	int		&$max_price			maximum filter price
 * @param	int		&$min_handle_price	minimum filter handle price
 * @param	int		&$max_handle_price	maximum filter handle price
 * @param	array	&$taxonomies		holds array of arrays
 * @return	array						holds array of arrays
 */
function AWPF_init_products_filter_values( $taxonomy, $taxonomy_term_id, &$min_price, &$max_price, &$min_handle_price, &$max_handle_price, &$taxonomies ) {
	global $woocommerce;
	
	$products = array();
	
	if ( ! $taxonomy || ! $taxonomy_term_id || count($taxonomies) == 0 )
		return $products;
	
	// Get all products related to taxonomy term ID
	$meta_query = $woocommerce->query->get_meta_query();
	
	$args = array(
		'post_type'			=> 'product',
		'posts_per_page'	=> -1,
		'no_found_rows'		=> true,
		'tax_query'			=> array(
			'relation'		=> 'AND',
			array(
				'taxonomy'	=> $taxonomy,
				'field'		=> 'id',
				'terms'		=> $taxonomy_term_id
			)
		),
		'meta_query'	=> $meta_query
	);

	$query = new WP_Query($args);
	
	// Fill in filter values and $products according to products meta data
	global $post;
	
	if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();
	
		// Get product price
		$_product = wc_get_product($post->ID);
		$price = round( $_product->get_price() );
		
		// Initiate $products and update product price and display option
		$products[$post->ID] = array();
		$products[$post->ID][0] = $price;
		$products[$post->ID][1] = 1;
		
		// Update price filter
		if ( is_null($min_price) || is_null($max_price) ) :
			$min_price = $max_price = $price;
		else :
			$min_price = min($price, $min_price);
			$max_price = max($price, $max_price);
		endif;

		// Initiate $products before input taxonomies data
		$products[$post->ID][2] = array();

		// Update taxonomies filter and $products taxonomies
		foreach ($taxonomies as $tax_name => &$tax_data) :
			// Initiate $products before input a single taxonomy data
			$products[$post->ID][2][$tax_name] = array();

			// Get all particular taxonomy terms associated with this product 
			$p_terms = wp_get_post_terms($post->ID, $tax_name);
			
			if ($p_terms)
				// Update $taxonomies counters
				// Increment number of products associated with this taxonomy
				$tax_data[1]++;
				
				foreach ($p_terms as $p_term) :
					// Store term ID in $products
					$products[$post->ID][2][$tax_name][] = $p_term->term_id;

					// Update $taxonomies counters
					// Increment number of products associated with this term
					$tax_data[2][$p_term->term_id][1]++;
				endforeach;
		endforeach;
		
	endwhile; endif; wp_reset_postdata();
	
	// Update price filter handles in first page load
	$min_handle_price = $min_price;
	$max_handle_price = $max_price;
	
	return $products;
}