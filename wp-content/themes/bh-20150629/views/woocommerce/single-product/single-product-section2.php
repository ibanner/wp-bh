<?php
/**
 * The template for displaying product content section 2 in the single-product.php template
 *
 * @author 		Beit Hatfutsot
 * @package 	bh/views/woocommerce/single-product
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post;

echo '<div class="row single-product-section single-product-section2">';

	/**
	 * BH_shop_before_experience hook
	 * 
	 * deprecated
	 *
	 * @hooked	BH_shop_toggle_experience - 10
	 */
	//do_action('BH_shop_before_experience');
	
	echo '<div id="experience">';
	
		/**
		 * BH_shop_experience hook
		 *
		 * //@hooked	BH_shop_show_random_image - 10
		 * //@hooked	BH_shop_show_experience_text - 20
		 * 
		 * @hooked	BH_shop_show_experience_banner - 10
		 */
		do_action('BH_shop_experience');
		
	echo '</div>';
	
echo '</div>';