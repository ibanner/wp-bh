<?php
/**
 * The template for displaying product content section 1 in the single-product.php template
 *
 * @author 		Beit Hatfutsot
 * @package 	bh/views/woocommerce/single-product
 * @version     2.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post;

?>

<div class="single-product-section single-product-section1">

	<div class="row">

		<div class="col-md-6 col-md-push-6 product-title">

			<?php get_template_part('views/woocommerce/single-product/single-product-section1', 'title'); ?>

		</div>

	</div>

	<div class="row">

		<div class="col-md-6 product-images">

			<?php
				/**
				 * BH_shop_before_single_product_meta hook
				 *
				 * @hooked	BH_shop_single_product_images - 10
				 */
				do_action('BH_shop_before_single_product_meta');
			?>

		</div>

		<div class="col-md-6 product-meta">

			<?php
				/**
				 * BH_shop_single_product_meta hook
				 *
				 * @hooked	woocommerce_template_single_price - 10
				 * @hooked	BH_shop_single_excerpt - 20
				 * @hooked	woocommerce_template_single_add_to_cart - 30
				 * @hooked	BH_shop_single_gift - 40
				 * @hooked	BH_shop_single_meta - 50
				 * @hooked	BH_shop_single_shipping - 60
				 */
				do_action('BH_shop_single_product_meta');
			?>

		</div>

	</div>

</div>