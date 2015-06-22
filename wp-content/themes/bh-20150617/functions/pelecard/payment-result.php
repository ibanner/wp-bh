<?php
/**
 * Pelecard iframe response
 *
 * @author 		Beit Hatfutsot
 * @package 	functions/pelecard
 * @version     1.0
 */

require_once('../../../../../wp-load.php'); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Untitled Document</title>
	
	<?php wp_head(); ?>
	
	<style type="text/css">
		<!--
		html {
			background: none;
		}
		-->
	</style>
	
</head>

<body>

<?php

	session_start();
	
	if ( ! isset($_POST['result']) || ! isset($_SESSION['payment_data']) )
		return;
		
	// collect data
	$result			= $_POST['result'];
	$resultCode		= substr($result, 0, 3);
	
	if ( $resultCode == '000' ) :
	
		// success
		
		if ( isset($_SESSION['microfilm-post']) ) :
		
			// microfilm payment
			
			// add credit card company name
			$_SESSION['payment_data']['cc_name']		= BH_get_cc_company($result);
			
			require('microfilm-payment-result.php');
			
			// prepare cfdb
			$posted_data = array();
			
			foreach ( $_SESSION['microfilm-post'] as $key => $val ) {
				if ( is_array($val) ) {
					// microfilms data
					for ($i=0 ; $i<count($val) ; $i++)
						if ( is_array($val[$i]) )
							// microfilm data
							foreach ( $val[$i] as $microfilm_key => $microfilm_val )
								$posted_data[$microfilm_key . '-' . $i] = $microfilm_val;
				} else {
					$posted_data[$key] = $val;
				}
			}
			
			$posted_data['total']						= $_SESSION['payment_data']['total']/100;
			$posted_data['transactionid']				= $_SESSION['payment_data']['transactionID'];
			$posted_data['paymentStep']					= 'completed';
			
			$cfdb_form_title = 'LDS Microfilms';
			
		else :
		
			// CF7 payment
			
			global $additional_posted_data;
			
			$contact_form	= unserialize( $_SESSION['payment_data']['contact_form'] );
			$form_instance	= unserialize( $_SESSION['payment_data']['form_instance'] );
			
			// update instance posted data and additional posted data with transaction info
			$posted_data								= $form_instance->get_posted_data();
			$posted_data['total']						= $_SESSION['payment_data']['total']/100;
			$posted_data['transactionid']				= $_SESSION['payment_data']['transactionID'];
			$additional_posted_data['paymentStep']		= 'completed';
			
			// update custom payment instance
			$custom_payment_key							= ( isset( $_SESSION['custom_payment_key'] ) && $_SESSION['custom_payment_key'] ) ? $_SESSION['custom_payment_key'] : ''; 
			$custom_payment								= ($custom_payment_key) ? $GLOBALS['customPaymentManager']->getCustomPayment($custom_payment_key) : '';
			
			if ($custom_payment) :
				$custom_payment->paid( $_SESSION['payment_data']['transactionID'] );
				
				// update instance posted data with custom payment issuer and customer Emails
				$posted_data['issuer-email']	= $_SESSION['payment_data']['issuer-email'];
				$posted_data['customer-email']	= $_SESSION['payment_data']['customer-email'];
			endif;
			
			// build mail messages
			$mail			= $contact_form->prop('mail');
			$mail_2			= $contact_form->prop('mail_2');
			
			$mail			= BH_build_message($mail, $posted_data);
			$mail_2			= BH_build_message($mail_2, $posted_data);
			
			// send mail messages
			$result = WPCF7_Mail::send($mail, 'mail');
			
			if ($result) {
				if ( $mail_2 && $mail_2['active'] ) {
					WPCF7_Mail::send( $mail_2, 'mail_2' );
				}
			}
			
			$cfdb_form_title = $contact_form->title();
			
		endif;
		
		// document the completed request
		$cfdb_data = (object) array (
			'title' => $cfdb_form_title,
			'posted_data' => $posted_data,
			'uploaded_files' => null
		);
		do_action_ref_array( 'cfdb_submit', array(&$cfdb_data) );
		
		// display payment approval
		echo '<h2>' . __('Thank you for your request!', 'BH') . '</h2>';
		echo '<p>' . __('An invoice for your payment will be sent to your email address shortly.', 'BH') . '</p>';
		
	else :
	
		// error
		echo '<h2>' . __('Payment failed', 'BH') . '</h2>';
		
		switch ( $resultCode ) :
		
			case '006' :
				// wrong ID or CVV
				echo '<p><strong>' . __('Error #', 'BH') . '006:</strong> ' . __('Wrong ID or CVV.', 'BH') . '</p>';
				echo '<p>' . __('Please correct the errors and submit the request again.', 'BH') . '</p>';
				
				break;
				
			case '033' :
			case '039' :
				// wrong credit card number
				echo '<p><strong>' . __('Error #', 'BH') . '033/039:</strong> ' . __('Wrong credit card number.', 'BH') . '</p>';
				echo '<p>' . __('Please correct the errors and submit the request again.', 'BH') . '</p>';
				
				break;
				
			case '101' :
				// unsupported credit card
				echo '<p><strong>' . __('Error #', 'BH') . '101:</strong> ' . __('Unsupported credit card.', 'BH') . '</p>';
				echo '<p>' . __('Please submit the request again using a different credit card.', 'BH') . '</p>';
				
				break;
				
			default :
				// general error
				echo '<p><strong>' . __('Error #', 'BH') . $resultCode . ':</strong> ' . __('General Error.', 'BH') . '</p>';
				
		endswitch;
	
	endif;
	
	// reset payment and microfilm session data
	$_SESSION['payment_data']	= null;
	$_SESSION['microfilm-post']	= null;
?>

</body>
</html>
