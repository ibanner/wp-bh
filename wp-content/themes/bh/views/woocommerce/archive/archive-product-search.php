<?php
/**
 * The Template for displaying product search page
 *
 * @author 		Beit Hatfutsot
 * @package 	bh/views/woocommerce/archive
 * @version     2.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php
	global $list, $ec_products;

	$search_title = BH_shop_archive_title();
?>

<div class="container">
	<div class="row shop-archive-section">

		<div class="col-sm-3">

			<div class="shop-sidebar">

				<div class="term-title-wrapper">
					<?php echo $search_title; ?>
				</div>

				<?php
					/**
					 * BH_shop_wswu_banner
					 */
					BH_shop_wswu_banner();
				?>

			</div>

		</div>

		<div class="col-sm-9">

			<?php if ( have_posts() ) : ?>
			
				<div class="products-list">
				
					<?php woocommerce_product_loop_start(); ?>
					
					<?php
						// for Google Analytics Enhanced Ecommerce - define list name and products array

						$list			= 'Product Search Results';
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
</div>