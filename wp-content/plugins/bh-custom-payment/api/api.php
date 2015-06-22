<?php

require_once('../../../../wp-load.php');
require_once('functions.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	if ( isset($_POST['action']) ) {

		switch($_POST['action']) {
			case 'create':

				$payment = create();

				if (!$payment) {

				}
				elseif ( $payment->data['sentTo'] == 'Not sent' ) {

				}
				else {

				}

				break;
			case 'resend':

				$message = resend() ? 'Email resent successfuly .' : 'Could not resend email.';

				echo json_encode(
					array(
						'message' => $message
					)
				);
				
				break;
			case 'delete':

				$message = delete() ? 'Payment delted.' : 'Could not delete payment.';

				echo json_encode(
					array(
						'message' => $message
					)
				);
				
				break; 
		}
	}
}