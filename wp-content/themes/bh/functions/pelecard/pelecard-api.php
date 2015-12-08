<?php
/**
 * Pelecard iframe 2 API
 *
 * @author		Beit Hatfutsot
 * @package		bh/functions/pelecard
 * @version		2.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * BH_pelecard_iframe
 * 
 * Get Pelecard iframe src
 * 
 * @param	string	$lang		language code (en/he)
 * @return	string				Pelecard iframe src or empty string in case of failure
 */
function BH_pelecard_iframe($lang) {
	$user		= get_field('acf-options_pelecard_user', 'option');
	$password	= get_field('acf-options_pelecard_password', 'option');
	$terminal	= get_field('acf-options_pelecard_terminal', 'option');

	if ( ! $user || ! $password || ! $terminal )
		return '';

	session_start();

	$data = array(
		'user'							=> $user,
		'password'						=> $password,
		'terminal'						=> $terminal,
		'GoodURL'						=> $_SESSION['payment_data']['goodUrl'],
		'ErrorURL'						=> $_SESSION['payment_data']['errorUrl'],
		'Total'							=> $_SESSION['payment_data']['total'],
		'Currency'						=> $_SESSION['payment_data']['currency'],
		'Language'						=> $lang,
		'CustomerIdField'				=> 'Hide',
		'Cvv2Field'						=> 'Hide',
		'FeedbackDataTransferMethod'	=> 'POST',
		'MaxPayments'					=> 1,
		'ParamX'						=> $_SESSION['payment_data']['transactionID'],
		'ShowXParam'					=> true,
		'CaptionSet'					=> array(
			'cs_xparam'					=> __('Transaction ID', 'BH')
		)
	);
	$jsonData = json_encode($data);

	$ch = curl_init('https://gateway20.pelecard.biz/PaymentGW/init');
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=UTF-8','Content-Length: ' . strlen($jsonData)));

	$result = curl_exec($ch);

	if ($result) {
		$serverData = json_decode($result,true);
		$result = $serverData['URL'];
	}

	return $result;
}

/**
 * BH_pelecard_payment_result
 * 
 * Get Pelecard payment result
 * 
 * @param	string	$transactionID		transaction ID recieved from success page
 * @return	array						Pelecard payment result or empty string in case of failure
 */
function BH_pelecard_payment_result($transactionID) {
	$user		= get_field('acf-options_pelecard_user', 'option');
	$password	= get_field('acf-options_pelecard_password', 'option');
	$terminal	= get_field('acf-options_pelecard_terminal', 'option');

	if ( ! $user || ! $password || ! $terminal )
		return '';

	$data = array(
		'user'				=> $user,
		'password'			=> $password,
		'terminal'			=> $terminal,
		'TransactionId'		=> $transactionID
	);
	$jsonData = json_encode($data);

	$ch = curl_init('https://gateway20.pelecard.biz/PaymentGW/GetTransaction');
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=UTF-8','Content-Length: ' . strlen($jsonData)));

	$result = curl_exec($ch);

	if ($result) {
		$serverData = json_decode($result,true);
		$result = $serverData;
	}

	return $result;
}

/**
 * BH_pelecard_validate_transaction
 * 
 * Validate transaction
 * 
 * @param	string	$confirmationKey	confirmation key recieved from success page
 * @param	string	$uniqueKey			UserKey recieved from success page (in case of an empty UserKey, send PelecardTransactionId)
 * @param	string	$total				order total
 * @return	int							1 => transcation validated / 0 => transcation not validated
 */
function BH_pelecard_validate_transaction($confirmationKey, $uniqueKey, $total) {
	if ( ! $confirmationKey || ! $uniqueKey || ! $total )
		return 0;

	$data = array(
		'ConfirmationKey'	=> $confirmationKey,
		'UniqueKey'			=> $uniqueKey,
		'TotalX100'			=> $total
	);
	$jsonData = json_encode($data);

	$ch = curl_init('https://gateway20.pelecard.biz/PaymentGW/ValidateByUniqueKey');
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=UTF-8','Content-Length: ' . strlen($jsonData)));

	$result = curl_exec($ch);

	return $result;
}