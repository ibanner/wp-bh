<?php
/**
 * The Template for displaying shop homepage
 *
 * @author 		Beit Hatfutsot
 * @package 	bh/views/woocommerce/archive
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php
	/**
	 * BH_shop_home hook
	 *
	 * @hooked	BH_shop_home_banners - 10
	 * @hooked	BH_shop_home_product_sliders - 20
	 */
	do_action('BH_shop_home');
?>