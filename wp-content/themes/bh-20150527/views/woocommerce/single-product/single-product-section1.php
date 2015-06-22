<?php
/**
 * The template for displaying product content section 1 in the single-product.php template
 *
 * @author 		Beit Hatfutsot
 * @package 	bh/views/woocommerce/single-product
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post;

echo '<div class="row single-product-section single-product-section1">';

	// shop body
	echo '<div class="col-sm-11 shop-body-left">';
	
		woocommerce_breadcrumb();
		
		echo '<div class="row">';
		
			echo '<div class="col-sm-7 product-images">';
			
				/**
				 * BH_shop_before_single_product_meta hook
				 *
				 * @hooked	BH_shop_show_product_images - 10
				 */
				do_action('BH_shop_before_single_product_meta');
				
			echo '</div>';
			
			echo '<div class="col-sm-5 product-meta">';
			
				/**
				 * BH_shop_single_product_meta hook
				 *
				 * @hooked	BH_shop_single_title - 5
				 * @hooked	woocommerce_template_single_price - 10
				 * @hooked	BH_shop_single_excerpt - 20
				 * @hooked	woocommerce_template_single_add_to_cart - 30
				 * @hooked	BH_shop_single_meta - 40
				 * @hooked	BH_shop_single_badges - 50
				 */
				do_action('BH_shop_single_product_meta');
				
			echo '</div>';
			
		echo '</div>';
		
	echo '</div>';
	
	// shop sidebar
	echo '<div class="col-sm-1 shop-body-right">';
	
		get_template_part('views/sidebar/sidebar-shop', 'recently-viewed');
			
	echo '</div>';
	
echo '</div>';