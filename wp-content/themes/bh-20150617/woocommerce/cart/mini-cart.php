<?php
/**
 * Mini-cart
 *
 * Contains the markup for the mini-cart, used by the cart widget
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<?php do_action( 'woocommerce_before_mini_cart' ); ?>

<ul class="cart_list product_list_widget <?php echo $args['list_class']; ?>">

	<?php if ( sizeof( WC()->cart->get_cart() ) > 0 ) : ?>

		<?php
			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
				$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				$product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

				if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) {

					$thumbnail     = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
					$product_price = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );

					?>
					<li class="product_list_widget_item">
						<a href="<?php echo get_permalink( $product_id ); ?>">
						
							<div class="col item-image">
								<div class="item-image-wrapper">
									<?php echo str_replace( array( 'http:', 'https:' ), '', $thumbnail ); ?>
								</div>
							</div>
							<div class="col item-data">
								<?php echo $_product->get_title(); ?>
								<?php echo WC()->cart->get_item_data( $cart_item ); ?>
		
								<?php echo apply_filters( 'woocommerce_widget_cart_item_quantity', '<span class="quantity">' . sprintf( __('Quantity: %s', 'BH'), $cart_item['quantity'] ) . '</span>', $cart_item, $cart_item_key ); ?>
							</div>
							<div class="col item-price">
								<?php echo $product_price; ?>
							</div>
							
						</a>
					</li>
					<?php
				}
			}
		?>

	<?php else : ?>

		<li class="empty"><?php _e( 'No products in the cart.', 'BH' ); ?></li>

	<?php endif; ?>

</ul><!-- end product list -->

<?php if ( sizeof( WC()->cart->get_cart() ) > 0 ) : ?>

	<div class="total">
		<div class="col title">
			<?php _e('Subtotal', 'BH'); ?>:
		</div>
		<div class="col sum">
			<?php echo WC()->cart->get_cart_subtotal(); ?>
		</div>
	</div>

	<?php do_action( 'woocommerce_widget_shopping_cart_before_buttons' ); ?>

	<div class="buttons">
		<a href="<?php echo WC()->cart->get_cart_url(); ?>" class="view-cart-btn"><?php _e( 'View Cart', 'BH' ); ?></a>
		<a href="<?php echo WC()->cart->get_checkout_url(); ?>" class="checkout-btn"><?php _e( 'Checkout', 'BH' ); ?></a>
	</div>

<?php endif; ?>

<?php do_action( 'woocommerce_after_mini_cart' ); ?>
