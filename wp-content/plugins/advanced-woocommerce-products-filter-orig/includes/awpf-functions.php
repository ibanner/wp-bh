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
 * awpf_init_categories
 *
 * Initiate $categories as an array of arrays (product categories)
 *
 * $categories structure:
 * ======================
 * $categories[ {category parent ID} ][][0]				=> category ID
 * $categories[ {category parent ID} ][][1]				=> category link
 * $categories[ {category parent ID} ][][2]				=> number of products associated with this category (including children)
 * $categories[ {category parent ID} ][][3]				=> whether this category is checked in subcategory filter [1 = true / 0 = false]
 * $categories[ {category parent ID} ][][4]				=> whether this category is an ancestor of the current category [true / false]
 *
 * @param	$show_categories_menu (bool)
 * @param	&$categories (array)
 * @return	n/a
 */
function awpf_init_categories($show_categories_menu, &$categories) {
	if ( ! $show_categories_menu )
		return;

	$args = array(
		'orderby'	=> 'term_order'
	);
	$terms = get_terms('product_cat', $args);

	if ($terms) {
		foreach ($terms as $term) {
			if ( ! array_key_exists($term->parent, $categories) ) {
				$categories[$term->parent] = array();
			}

			$categories[$term->parent][] = array(
				0 => $term->term_id,
				1 => get_term_link($term),
				2 => $term->count,
				3 => $taxonomy == 'product_cat' && ( $term->term_id == $term_id ) ? 1 : 0,
				4 => $taxonomy == 'product_cat' && ( $term->term_id == $term_id || cat_is_ancestor_of($term->term_id, $term_id) )
			);
		}
	}
}

/**
 * awpf_init_taxonomies
 *
 * Initiate $taxonomies as an array of arrays (taxonomies and terms data)
 *
 * $taxonomies structure:
 * ======================
 * $taxonomies[ {taxonomy name} ][0]					=> taxonomy filter title
 * $taxonomies[ {taxonomy name} ][1]					=> number of products associated with this taxonomy
 * $taxonomies[ {taxonomy name} ][2][ {term ID} ][0]	=> number of products associated with this term
 * $taxonomies[ {taxonomy name} ][2][ {term ID} ][1]	=> whether this term is checked in taxonomy filter [1 = true / 0 = false]
 *
 * @param	$tax_list (array)
 * @param	&$taxonomies (array)
 * @return	n/a
 */
function awpf_init_taxonomies($tax_list, &$taxonomies) {
	if ( ! $tax_list )
		return;

	foreach ($tax_list as $tax) {
		// Get taxonomy terms
		$args = array(
			'orderby'	=> 'term_order'
		);
		$terms = get_terms($tax['name'], $args);
		
		if ($terms) {
			$taxonomies[ $tax['name'] ] = array(
				0 => $tax['title'],
				1 => 0,
				2 => array()
			);

			foreach ($terms as $term) {
				$taxonomies[ $tax['name'] ][2][$term->term_id] = array(
					0 => 0,
					1 => 0
				);
			}
		}
	}
}

/**
 * awpf_init_products_filter_values
 * 
 * Initiate products filter values according to current taxonomy, term ID, price range and taxonomy terms
 * 
 * $taxonomies structure:
 * ======================
 * $taxonomies[ {taxonomy name} ][0]					=> taxonomy filter title
 * $taxonomies[ {taxonomy name} ][1]					=> number of products associated with this taxonomy
 * $taxonomies[ {taxonomy name} ][2][ {term ID} ][0]	=> number of products associated with this term
 * $taxonomies[ {taxonomy name} ][2][ {term ID} ][1]	=> whether this term is checked in taxonomy filter [1 = true / 0 = false]
 *
 * $products structure:
 * ====================
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
function awpf_init_products_filter_values($taxonomy, $taxonomy_term_id, &$min_price, &$max_price, &$min_handle_price, &$max_handle_price, &$taxonomies) {
	global $woocommerce;
	
	$products = array();
	
	// Get all products related to taxonomy term ID
	$meta_query = $woocommerce->query->get_meta_query();
	
	$args = array(
		'post_type'			=> 'product',
		'posts_per_page'	=> -1,
		'no_found_rows'		=> true,
		'meta_query'		=> $meta_query
	);

	if ($taxonomy && $taxonomy_term_id) {
		$args['tax_query']	= array(
			array(
				'taxonomy'	=> $taxonomy,
				'field'		=> 'id',
				'terms'		=> $taxonomy_term_id
			)
		);
	}

	$query = new WP_Query($args);
	
	// Fill in filter values and $products according to products meta data
	global $post;
	
	if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();
	
		// Get product price
		$_product = wc_get_product($post->ID);
		$price = round( $_product->get_price() );
		
		// Initiate $products and update product price and product visibility
		$products[$post->ID] = array(
			0 => $price,
			1 => 1
		);
		
		// Update price filter
		if ( is_null($min_price) || is_null($max_price) ) {
			$min_price = $max_price = $price;
		} else {
			$min_price = min($price, $min_price);
			$max_price = max($price, $max_price);
		}

		// Initiate $products before input taxonomies data
		$products[$post->ID][2] = array();

		// Update taxonomies filter and $products taxonomies
		if ($taxonomies) {
			foreach ($taxonomies as $tax_name => &$tax_data) {
				// Initiate $products before input a single taxonomy data
				$products[$post->ID][2][$tax_name] = array();

				// Get all particular taxonomy terms associated with this product 
				$p_terms = wp_get_post_terms($post->ID, $tax_name);
				
				if ($p_terms) {
					// Update $taxonomies counters
					// Increment number of products associated with this taxonomy
					$tax_data[1]++;
					
					foreach ($p_terms as $p_term) {
						// Store term ID in $products
						$products[$post->ID][2][$tax_name][] = $p_term->term_id;

						// Update $taxonomies counters
						// Increment number of products associated with this term
						$tax_data[2][$p_term->term_id][0]++;
					}
				}
			}
		}
		
	endwhile; endif; wp_reset_postdata();
	
	// Update price filter handles in first page load
	$min_handle_price = $min_price;
	$max_handle_price = $max_price;
	
	return $products;
}

/**
 * awpf_product_categories_menu_item
 *
 * Recursive function handles display of a single category menu item.
 * The recursive part handles the children menu items (if there are any) of this parent category 
 *
 * @param	array	$categories		holds array of $category arrays
 * @param	array	$category		holds array of a single category data
 * @param	int		$depth			indicates current menu depth
 */
function awpf_product_categories_menu_item($categories, $category, $depth) {
	$has_children = array_key_exists($category[0], $categories);
	$classes = array();

	if ($has_children)
		$classes[] = 'has-children';

	if ( $category[4] )
		$classes[] = 'ancestor';

	echo '<li class="' . implode(' ', $classes) . '">';
		if ($has_children || $depth == 0) {
			// Top level item and/or a parent item
			echo '<span class="item-before"></span>';
			echo '<a><span>' . get_cat_name( $category[0] ) . '</span> <span class="count">(' . $category[2] . ')</span></a>';
		} else {
			// Low level item without children
			echo '<label>';
				echo '<input type="checkbox" name="product_cat-' . $category[0] . '" id="product_cat-' . $category[0] . '" value="product_cat-' . $category[0] . '" />' . get_cat_name( $category[0] ) . ' <span class="count">(' . $category[2] . ')</span>';
			echo '</label>';
		}

		if ($has_children) {
			// Start a subcategories menu
			echo '<ul class="children children-depth-' . $depth . '">';
				// Display an "All" item as a first item in the subcategories menu
				echo '<li>';
					echo '<label>';
						echo '<input type="checkbox" name="product_cat-' . $category[0] . '-all' . '" id="product_cat-' . $category[0] . '-all' . '" value="product_cat-' . $category[0] . '-all' . '"' . ( $category[3] ? ' checked' : '' ) . ' />' . apply_filters( 'awpf_all_subcategories_title', __('All', 'awpf') ) . ' <span class="count">(' . $category[2] . ')</span>';
					echo '</label>';
				echo '</li>';

				foreach ( $categories[$category[0]] as $subcategory ) {
					awpf_product_categories_menu_item($categories, $subcategory, $depth+1);
				}
			echo '</ul>';
		}
	echo '</li>';
}