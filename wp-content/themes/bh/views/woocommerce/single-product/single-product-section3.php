<?php
/**
 * The template for displaying product content section 3 in the single-product.php template
 *
 * @author 		Beit Hatfutsot
 * @package 	bh/views/woocommerce/single-product
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post;

echo '<div class="row single-product-section single-product-section3">';

	/**
	 * BH_shop_related_products
	 *
	 * @hooked	BH_shop_show_related_products - 10
	 */
	do_action('BH_shop_related_products');
	
echo '</div>';