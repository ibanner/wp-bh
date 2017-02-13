<?php
/**
 * The Template for displaying shop homepage categories menu
 *
 * @author 		Beit Hatfutsot
 * @package 	bh/views/woocommerce/archive
 * @version     2.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$categories		= get_field('acf-options_shop_include_categories', 'option');
$manual_item	= get_field('acf-options_shop_show_manual_item', 'option');

if ( ! $categories && ! $manual_item )
	return;

$categories_count = $categories ? count($categories) : 0;
$index = 0;

$manual_item_title		= get_field('acf-options_shop_manual_item_title', 'option');
$manual_item_subtext	= get_field('acf-options_shop_manual_item_subtext', 'option');
$manual_item_link		= get_field('acf-options_shop_manual_item_link', 'option');

echo '<div class="container shop-categories-menu-wrapper visible-sm visible-xs">';

	// Categories links
	if ($categories_count) :
		foreach ($categories as $category) :

			$icon = get_field('acf-product-category-shop_homepage_menu_icon', 'product_cat_' . $category->term_id);

			echo '<div class="category-item-wrapper ' . ( ++$index%2 == 0 ? 'even' : 'odd' ) . '">';
				echo '<a href="' . get_term_link($category) . '">';
					echo '<div class="category-item">';
						echo '<div class="title">' . $category->name . '</div>';
						echo $icon ? '<span class="icon" style="background-image: url(\'' . $icon . '\');"></span>' : '';
					echo '</div>';
				echo '</a>';
			echo '</div>';

		endforeach;
	endif;

	// Manual link
	if ( $manual_item && $manual_item_title && $manual_item_link ) :

		echo '<div class="category-item-wrapper manual-item-wrapper ' . ( ! $categories_count || $categories_count%2 == 0 ? 'single-line ' : ' ' ) . ( ++$index%2 == 0 ? 'even' : 'odd' ) . '">';
			echo '<a href="' . get_permalink($manual_item_link) . '">';
				echo '<div class="category-item">';
					echo '<div class="title">' . $manual_item_title . '</div>';
					echo $manual_item_subtext ? '<div class="subtext">' . $manual_item_subtext . '</div>' : '';
					echo '<span class="icon"></span>';
				echo '</div>';
			echo '</a>';
		echo '</div>';

	endif;

echo '</div>';