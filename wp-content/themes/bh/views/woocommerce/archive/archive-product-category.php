<?php
/**
 * The Template for displaying product archives
 *
 * @author 		Beit Hatfutsot
 * @package 	bh/views/woocommerce/archive
 * @version     2.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php
	global $wp_query, $list, $ec_products;
	
	$category = $wp_query->get_queried_object();
	$cat_name = $category ? $category->name : '';
?>

<div class="container">
	<div class="row shop-archive-section">
	
		<div class="col-sm-3">

			<?php get_template_part('views/sidebar/sidebar-shop', 'refine-products'); ?>

		</div>
		
		<div class="col-sm-9">

			<?php
				/**
				 * BH_shop_product_cat_banner
				 */
				BH_shop_product_cat_banner();
			?>

			<?php if ( have_posts() ) : ?>

				<div class="products-list">

					<?php
						/**
						 * woocommerce_before_shop_loop hook
						 *
						 * @hooked woocommerce_catalog_ordering - 30
						 */
						do_action('woocommerce_before_shop_loop');
					?>

					<?php woocommerce_product_loop_start(); ?>

					<?php
						// for Google Analytics Enhanced Ecommerce - define list name and products array
						$list			= $cat_name ? 'Product Category: ' . $cat_name : 'Product Category';
						$ec_products	= array();
					?>

					<?php while ( have_posts() ) : the_post(); ?>

						<?php wc_get_template_part('content', 'product'); ?>

					<?php endwhile; // end of the loop. ?>

					<?php woocommerce_product_loop_end(); ?>

					<?php get_template_part('views/components/not-found'); ?>

				</div>

				<script>
					jQuery(function($) {
						BH_EC_onListView(<?php echo json_encode($ec_products); ?>, '<?php echo get_woocommerce_currency(); ?>', true);
					});
				</script>

			<?php else : ?>

				<?php get_template_part('views/components/not-found'); ?>

			<?php endif; ?>
				
		</div>

	</div>

	<?php get_template_part('views/sidebar/sidebar-shop', 'recently-viewed'); ?>
</div>