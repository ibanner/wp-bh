<?php
/**
 * AWPF - functions
 *
 * @author 		Nir Goldberg
 * @package 	includes
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * awpf_product_categories_menu_item
 *
 * Recursive function handles display of a single category menu item.
 * The recursive part handles the children menu items (if there are any) of this parent category 
 *
 * @since		1.0
 * @param		$categories (array) holds array of $category arrays
 * @param		$category (array) holds array of a single category data
 * @param		$depth (int) indicates current menu depth
 * @return		N/A
 */
function awpf_product_categories_menu_item( $categories, $category, $depth ) {

	$has_children = array_key_exists( $category[0], $categories );
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

		}
		else {

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
					awpf_product_categories_menu_item( $categories, $subcategory, $depth+1 );
				}
			echo '</ul>';

		}

	echo '</li>';

}