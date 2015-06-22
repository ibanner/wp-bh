<?php

require_once('functions.php');

?>

<div id="bh-custom-payment-admin">
	<div id="bh-custom-payment-admin-container">

		<form method="post" action="">
			<div>
			<label for="payment-key">Payment Key</label><br />
			<input id="payment-key" name="payment-key" readonly="true" value="<?php echo create_payment_key(12); ?>" required />
			</div>
			<div>
			<label for="issuer-name">Issuer Name</label><br />
			<input id="issuer-name" name="issuer-name" required />
			</div>
			<div>
			<label for="issuer-email">Issuer Email</label><br />
			<input id="issuer-email" name="issuer-email" required email />
			</div>
			<div>
			<label for="customer-name">Visitor / Customer Name</label><br />
			<input id="customer-name" name="customer-name" required />
			</div>
			<div>
			<label for="customer-name">Visitor / Customer Email</label><br />
			<input id="customer-email" name="customer-email" required email />
			</div>
			<div>
			<label for="transaction-key">Transaction Key</label><br />
			<input id="transaction-key" name="transaction-key" required />
			</div>
			<div>
			<label for="total">Total</label><br />
			<input id="total" name="total" required />
			</div>
			<input type="submit" value="create">
		</form>

		<table>
			<thead>
				<th>Payment Key</th>
				<th>Issuer Name</th>
				<th>Issuer Email</th>
				<th>Customer Name</th>
				<th>Customer Email</th>
				<th>Transaction Key</th>
				<th>Total</th>
				<th>Date Issued</th>
				<th>Sent To</th>
				<th>Paid</th>
				<th>Confirmation</th>
			</thead>
			<tbody>

			<span class="message"></span>
			
			<?php 

			$payments = $GLOBALS['customPaymentManager']->getData();

			foreach($payments as $payment) {
				echo '<tr>'.
					'<td>'					. $payment['paymentKey']			. '</td>'.
					'<td>' 					. $payment['issuerName']			. '</td>'.
					'<td>' 					. $payment['issuerEmail']			. '</td>'.
					'<td>' 					. $payment['customerName'] 			. '</td>'.
					'<td><input class="customer-email-resend" value="' 	. $payment['customerEmail'] 	. '" /></td>'.
					'<td>' 					. $payment['transactionKey'] 		. '</td>'.
					'<td>' 					. $payment['total'] 				. '</td>'.
					'<td>' 					. $payment['timeIssued'] 			. '</td>'.
					'<td>' 					. $payment['sentTo'] 				. '</td>'.
					'<td>' 					. parse_bool( $payment['paid'] )	. '</td>'.
					'<td>' 					. $payment['confirmation'] 			. '</td>';

				echo $payment['paid'] ? '<td></td>' : '<td class="resend-wrapper"><button class="resend"  key="' . $payment['paymentKey'] . '">Resend Email</button></td>';
				echo $payment['paid'] ? '<td></td>' : '<td class="resend-wrapper"><button class="delete"  key="' . $payment['paymentKey'] . '">Delete</button></td>';
				echo '</tr>';
			}

			echo '</tbody>'. 
				'</table>';

			?>

			</tbody>
		</table>

	</div>
</div>
