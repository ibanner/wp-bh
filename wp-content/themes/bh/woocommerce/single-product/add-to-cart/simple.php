<?php
/**
 * Simple product add to cart
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

if ( ! $product->is_purchasable() ) {
	return;
}

?>

<?php
	// Availability
	$availability      = $product->get_availability();
	$availability_html = empty( $availability['availability'] ) ? '' : '<p class="stock ' . esc_attr( $availability['class'] ) . '">' . esc_html( $availability['availability'] ) . '</p>';

	echo apply_filters( 'woocommerce_stock_html', $availability_html, $availability['availability'], $product );
?>

<?php if ( $product->is_in_stock() ) : ?>

	<?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>
	
	<?php
		// Enhanced Ecommerce - "add to cart" event tracking
		// collect product info and submit it on form submission
		
		$p_id		= $product->id;
		$p_sku		= esc_js( $product->sku );
		$p_name		= esc_js( $product->get_title() );
		$p_price	= number_format((float)$product->price, 2, '.', '');
		$p_currency	= get_woocommerce_currency();
		
		$category = '';
		$product_cats = wp_get_post_terms($p_id, 'product_cat');
		if ( $product_cats && ! is_wp_error ($product_cats) ) :
			$single_cat	= array_shift($product_cats);
			$category	= esc_js( $single_cat->name );
		endif;
	?>

	<form class="cart" method="post" enctype='multipart/form-data' onsubmit="BH_EC_onUpdateCart('<?php echo $p_sku; ?>', '<?php echo $p_name; ?>', '<?php echo $category; ?>', '<?php echo $p_price; ?>', '<?php echo $p_currency; ?>', $(this).find('.quantity input[type=number]').val(), 'add'); BH_FB_onAddToCart('<?php echo $p_id; ?>', '<?php echo $p_name; ?>', '<?php echo $category; ?>', '<?php echo $p_price; ?>', '<?php echo $p_currency; ?>'); return true;">
	 	<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

	 	<?php
	 		if ( ! $product->is_sold_individually() )
	 			woocommerce_quantity_input( array(
	 				'min_value' => apply_filters( 'woocommerce_quantity_input_min', 1, $product ),
	 				'max_value' => apply_filters( 'woocommerce_quantity_input_max', $product->backorders_allowed() ? '' : $product->get_stock_quantity(), $product )
	 			) );
	 	?>

	 	<input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $product->id ); ?>" />

	 	<button type="submit" class="single_add_to_cart_button button alt"><?php echo $product->single_add_to_cart_text(); ?></button>

		<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
	</form>

	<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>

<?php endif; ?>
