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
	global $wp_query, $list, $ec_products, $featured_product;
	
	$tt = $wp_query->get_queried_object();
	$tt_name = $tt ? $tt->name : '';

	$tt_title = BH_shop_tt_title();
?>

<div class="container">
	<div class="row shop-archive-section">
	
		<div class="col-sm-3">

			<div class="shop-sidebar">

				<div class="term-title-wrapper hidden-xs">
					<?php echo $tt_title; ?>
				</div>

				<?php
					/**
					 * BH_shop_sidebar hook
					 *
					 * @hooked BH_shop_products_filter	- 10
					 * @hooked BH_shop_wswu_banner		- 20
					 */
					do_action('BH_shop_sidebar');
				?>

				<div class="term-title-wrapper visible-xs">
					<?php echo $tt_title; ?>
				</div>

			</div>

		</div>
		
		<div class="col-sm-9">

			<?php
				/**
				 * BH_shop_tt_banner
				 */
				BH_shop_tt_banner();
			?>

			<?php if ( have_posts() ) : ?>

				<?php
					// check if fetured image should be displayed
					$total_products		= $wp_query->found_posts;
					$featured_product	= $total_products >= 3 ? true : false;
				?>

				<div class="products-list">

					<?php
						if ( 1 != $wp_query->found_posts && woocommerce_products_will_display() ) {
							echo '<div class="sort-options">';

								echo '<span class="sort-title">' . __('Sort by', 'BH') . ': </span>';

								/**
								 * woocommerce_before_shop_loop hook
								 *
								 * @hooked woocommerce_catalog_ordering - 30
								 */
								do_action('woocommerce_before_shop_loop');

							echo '</div>';
						}
					?>

					<?php woocommerce_product_loop_start(); ?>

					<?php
						// for Google Analytics Enhanced Ecommerce - define list name and products array
						$list			= $tt_name ? 'Product Archive: ' . $tt_name : 'Product Archive';
						$ec_products	= array();
					?>

					<?php while ( have_posts() ) : the_post(); ?>

						<?php
							wc_get_template_part('content', 'product');
							$featured_product = false;
						?>

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

	<?php //get_template_part('views/sidebar/sidebar-shop', 'recently-viewed'); ?>
</div>