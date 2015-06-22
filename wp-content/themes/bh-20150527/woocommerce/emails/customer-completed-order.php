<?php
/**
 * Customer completed order email
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title><?php echo get_bloginfo( 'name', 'display' ); ?></title>
	</head>
	<body style="font-family: Arial, Helvetica, Sans-serif; text-align: <?php echo is_rtl() ? 'right' : 'left'; ?>" <?php echo is_rtl() ? 'rightmargin' : 'leftmargin'; ?>="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
		<div id="wrapper">
			<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
				<tr>
					<td align="center" valign="top">
						<table border="0" cellpadding="0" cellspacing="0" width="600" id="template_container">
							<tr>
								<td align="center" valign="top">
									<!-- Header -->
									<table border="0" cellpadding="0" cellspacing="0" width="600" id="template_header">
										<tr>
											<td>
												<img src="<?php echo get_field('acf-email_customer_completed_order_title', 'option'); ?>" width="600" height="100" />
											</td>
										</tr>
									</table>
									<!-- End Header -->
								</td>
							</tr>
							<tr>
								<td align="center" valign="top">
									<!-- Body -->
									<table border="0" cellpadding="0" cellspacing="0" width="600" id="template_body">
										<tr>
											<td valign="top" id="body_content">
												<!-- Content -->
												<table border="0" cellpadding="20" cellspacing="0" width="100%">
													<tr>
														<td valign="top">
															<div id="body_content_inner">
															
																<p style="font-family: Arial, Helvetica, Sans-serif; text-align: <?php echo is_rtl() ? 'right' : 'left'; ?>"><?php _e( "Your order was shipped. Your order details are shown below for your reference:", 'woocommerce' ); ?></p>
																
																<?php do_action( 'woocommerce_email_before_order_table', $order, $sent_to_admin, $plain_text ); ?>
																
																<h2 style="font-family: Arial, Helvetica, Sans-serif; text-align: <?php echo is_rtl() ? 'right' : 'left'; ?>"><?php printf( __( 'Order #%s', 'woocommerce' ), $order->get_order_number() ); ?></h2>
																
																<table cellspacing="0" cellpadding="6" style="font-family: Arial, Helvetica, Sans-serif; width: 100%; border: 1px solid #eee;" border="1" bordercolor="#eee">
																	<thead>
																		<tr>
																			<th scope="col" style="font-family: Arial, Helvetica, Sans-serif; text-align: <?php echo is_rtl() ? 'right' : 'left'; ?>; border: 1px solid #eee;"><?php _e( 'Product', 'woocommerce' ); ?></th>
																			<th scope="col" style="font-family: Arial, Helvetica, Sans-serif; text-align: <?php echo is_rtl() ? 'right' : 'left'; ?>; border: 1px solid #eee;"><?php _e( 'Quantity', 'woocommerce' ); ?></th>
																			<th scope="col" style="font-family: Arial, Helvetica, Sans-serif; text-align: <?php echo is_rtl() ? 'right' : 'left'; ?>; border: 1px solid #eee;"><?php _e( 'Price', 'woocommerce' ); ?></th>
																		</tr>
																	</thead>
																	<tbody>
																		<?php echo $order->email_order_items_table( true, false, true ); ?>
																	</tbody>
																	<tfoot>
																		<?php
																			if ( $totals = $order->get_order_item_totals() ) {
																				$i = 0;
																				foreach ( $totals as $total ) {
																					$i++;
																					?><tr>
																						<th scope="row" colspan="2" style="font-family: Arial, Helvetica, Sans-serif; text-align: <?php echo is_rtl() ? 'right' : 'left'; ?>; border: 1px solid #eee; <?php if ( $i == 1 ) echo 'border-top-width: 4px;'; ?>"><?php echo $total['label']; ?></th>
																						<td style="font-family: Arial, Helvetica, Sans-serif; text-align: <?php echo is_rtl() ? 'right' : 'left'; ?>; border: 1px solid #eee; <?php if ( $i == 1 ) echo 'border-top-width: 4px;'; ?>"><?php echo $total['value']; ?></td>
																					</tr><?php
																				}
																			}
																		?>
																	</tfoot>
																</table>
																
																<?php do_action( 'woocommerce_email_after_order_table', $order, $sent_to_admin, $plain_text ); ?>
																
																<?php do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text ); ?>
																
																<?php do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text ); ?>
																
																<p style="font-family: Arial, Helvetica, Sans-serif; text-align: <?php echo is_rtl() ? 'right' : 'left'; ?>">For further questions or assistance please contact us: <a href="mailto:service@bh.org.il">service@bh.org.il</a></p>
																
																<?php do_action( 'woocommerce_email_footer' ); ?>
