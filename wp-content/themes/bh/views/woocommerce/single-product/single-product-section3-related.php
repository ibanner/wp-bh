<?php
/**
 * Single Product - related products
 *
 * @author 		Beit Hatfutsot
 * @package 	bh/views/woocommerce/single-product
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product, $woocommerce;

// get upsells
$upsells = $product->get_upsells();

if ( ! $upsells ) {
	// get related products
	$related = $product->get_related();

	if ( ! $related )
		return;
}

$slider_products = array();

// for Google Analytics Enhanced Ecommerce - define list name and products array
global $list, $ec_products;

$list			= 'Related Products';
$ec_products	= array();

$meta_query = $woocommerce->query->get_meta_query();

$args = array (
	'post_type'				=> 'product',
	'ignore_sticky_posts'	=> 1,
	'no_found_rows'			=> true,
	'posts_per_page'		=> -1,
	'orderby'				=> 'rand',
	'post__in'				=> $upsells ? $upsells : $related,
	'post__not_in'			=> array($product->id),
	'meta_query'			=> $meta_query
);
$slider_products_query = new WP_Query($args);

if ( $slider_products_query->have_posts() ) : while( $slider_products_query->have_posts() ) : $slider_products_query->the_post();
	$slider_products[] = $post;
endwhile; endif; wp_reset_postdata();

if ($slider_products) {

	$slider_index = 1;

	echo '<div class="products-slider">';
		echo '<h2>' . __('You might also like', 'BH') . '</h2>';
		
		// display upsells/related products as a slider
		include( locate_template('views/woocommerce/products-slider.php') );
	echo '</div>'; ?>

	<script>
		jQuery(function($) {
			BH_EC_onListView(<?php echo json_encode($ec_products); ?>, '<?php echo get_woocommerce_currency(); ?>', false);
		});
	</script>

<?php }