<?php
/**
 * The template for displaying product content section 3 in the single-product.php template
 *
 * @author 		Beit Hatfutsot
 * @package 	bh/views/woocommerce/single-product
 * @version     2.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post;

?>

<div class="single-product-section single-product-section3">

	<?php
		/**
		 * BH_shop_related_products
		 *
		 * @hooked	BH_shop_single_related_products - 10
		 */
		do_action('BH_shop_related_products');
	?>
	
</div>