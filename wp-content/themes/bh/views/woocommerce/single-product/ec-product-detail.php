<?php
/**
 * Enhanced Ecommerce - Measuring a Product Details View
 * Facebook Pixel - ViewContent event
 *
 * @author 		Beit Hatfutsot
 * @package 	bh/views/woocommerce/single-product
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post;

$_product = wc_get_product($post->ID);
$p_sku		= esc_js( $_product->sku );
$p_name		= esc_js( $_product->get_title() );
$p_price	= number_format((float)$_product->price, 2, '.', '');
$p_currency	= get_woocommerce_currency();

$category = '';
$product_cats = wp_get_post_terms($post->ID, 'product_cat');
if ( $product_cats && ! is_wp_error ($product_cats) ) :
	$single_cat	= array_shift($product_cats);
	$category	= esc_js( $single_cat->name );
endif;

?>

<script>
	jQuery(function($) {
		BH_EC_onProductDetail('<?php echo $p_sku; ?>', '<?php echo $p_name; ?>', '<?php echo $category; ?>', '<?php echo $p_price; ?>', '<?php echo $p_currency; ?>', true);
	});
</script>

<!-- Facebook Pixel Code - ViewContent event -->
<script>
	fbq('track', 'ViewContent', {
		content_type: 'product',
		content_ids: ['<?php echo $post->ID; ?>'],
		content_name: '<?php echo $p_name; ?>',
		content_category: '<?php echo $category; ?>',
		value: <?php echo $p_price; ?>,
		currency: '<?php echo $p_currency; ?>'
	});
</script>
<!-- End Facebook Pixel Code - ViewContent event -->