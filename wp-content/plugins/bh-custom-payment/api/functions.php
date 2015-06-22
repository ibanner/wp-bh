<?php

$customPaymentManager = $GLOBALS['customPaymentManager'];

function create() {
	global $customPaymentManager;

	$data = array(
		'paymentKey' 		=> $_POST['form']['payment-key'],
		'issuerName' 		=> $_POST['form']['issuer-name'],
		'issuerEmail' 		=> $_POST['form']['issuer-email'],
		'customerName'		=> $_POST['form']['customer-name'],
		'customerEmail'		=> $_POST['form']['customer-email'],
		'transactionKey'	=> $_POST['form']['transaction-key'],
		'total'				=> $_POST['form']['total'],
		'timeIssued' 		=> current_time('mysql'),
		'sentTo' 			=> 'Not sent',
		'paid' 				=> 0,
		'Confirmation'		=> ''
	);

	$payment = $customPaymentManager->createCustomPayment($data);

	if ($payment) {
		
		$payment->send();
		
		return $payment;	
	} 

	return false;
}

function resend() {
	global $customPaymentManager;

	$payment = $customPaymentManager->getCustomPayment($_POST['key']);

	return $payment? $payment->resend($_POST['email']) : false;
}

function delete() {
	global $customPaymentManager;

	$payment = $customPaymentManager->getCustomPayment($_POST['key']);

	return $payment ? $payment->delete() : false;	
}