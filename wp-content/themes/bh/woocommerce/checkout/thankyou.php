<?php
/**
 * Thankyou page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( $order ) : ?>

	<?php if ( $order->has_status( 'failed' ) ) : ?>

		<p><?php _e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction.', 'woocommerce' ); ?></p>

		<p><?php
			if ( is_user_logged_in() )
				_e( 'Please attempt your purchase again or go to your account page.', 'woocommerce' );
			else
				_e( 'Please attempt your purchase again.', 'woocommerce' );
		?></p>

		<p>
			<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php _e( 'Pay', 'woocommerce' ) ?></a>
			<?php if ( is_user_logged_in() ) : ?>
			<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="button pay"><?php _e( 'My Account', 'woocommerce' ); ?></a>
			<?php endif; ?>
		</p>

	<?php else : ?>

		<p><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Thank you. Your order has been received.', 'woocommerce' ), $order ); ?></p>

		<ul class="order_details">
			<li class="order">
				<?php _e( 'Order Number:', 'woocommerce' ); ?>
				<strong><?php echo $order->get_order_number(); ?></strong>
			</li>
			<li class="date">
				<?php _e( 'Date:', 'woocommerce' ); ?>
				<strong><?php echo date_i18n( get_option( 'date_format' ), strtotime( $order->order_date ) ); ?></strong>
			</li>
			<li class="total">
				<?php _e( 'Total:', 'woocommerce' ); ?>
				<strong><?php echo $order->get_formatted_order_total(); ?></strong>
			</li>
			<?php if ( $order->payment_method_title ) : ?>
			<li class="method">
				<?php _e( 'Payment Method:', 'woocommerce' ); ?>
				<strong><?php echo $order->payment_method_title; ?></strong>
			</li>
			<?php endif; ?>
		</ul>
		<div class="clear"></div>
		
		<?php
			// for Google Analytics Enhanced Ecommerce - collect transaction level information and cart data
			
			// transaction level information
			$transaction	= array();
			
			$transaction[0]['id']		= (string)$order->get_order_number();
			$transaction[0]['total']	= (string)$order->get_total();
			$transaction[0]['tax']		= (string)$order->get_total_tax();
			$transaction[0]['shipping']	= (string)$order->get_total_shipping();
			
			// cart data
			$order_items	= $order->get_items();
			$cart			= array();
			$items_ids		= array();
			$index			= 0;
			
			if ($order_items) {
				foreach ($order_items as $item) {
					$cart[$index]	= array();
					$product		= wc_get_product( $item['product_id'] );
					
					$category = '';
					$product_cats = wp_get_post_terms($item['product_id'], 'product_cat');
					if ( $product_cats && ! is_wp_error ($product_cats) ) :
						$single_cat	= array_shift($product_cats);
						$category	= esc_js( $single_cat->name );
					endif;
					
					$cart[$index]['sku']		= $items_ids[] = $product->sku;
					$cart[$index]['name']		= $product->get_title();
					$cart[$index]['category']	= $category;
					$cart[$index]['price']		= number_format((float)$product->price, 2, '.', '');
					$cart[$index]['quantity']	= (int)$item['qty'];
					
					$index++;
				}
			}
			
			// send order and cart data to Google Analytics
			if ($transaction && $cart) { ?>
				<script>
					jQuery(function($) {
						BH_EC_onTransaction(<?php echo json_encode($cart); ?>, <?php echo json_encode($transaction); ?>, '<?php echo get_woocommerce_currency(); ?>', true);
					});
				</script>

				<!-- Facebook Pixel Code - Purchase event -->
				<script>
					fbq('track', 'Purchase', {
						content_type: 'product',
						content_ids: ['<?php echo implode("', '" , $items_ids); ?>'],
						value: <?php echo $transaction[0]['total']; ?>,
						currency: '<?php echo get_woocommerce_currency(); ?>'
					});
				</script>
				<!-- End Facebook Pixel Code - Purchase event -->

				<!-- Facebook Conversion Code -->
				<script>
					window._fbq.push(['track', '6025740659065', {'value':'<?php echo $transaction[0]['total']; ?>','currency':'<?php echo get_woocommerce_currency(); ?>'}]);
				</script>
				<!-- End Facebook Conversion Code -->

			<?php }
		?>

	<?php endif; ?>

	<?php do_action( 'woocommerce_thankyou_' . $order->payment_method, $order->id ); ?>
	<?php do_action( 'woocommerce_thankyou', $order->id ); ?>

<?php else : ?>

	<p><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Thank you. Your order has been received.', 'woocommerce' ), null ); ?></p>

<?php endif; ?>
