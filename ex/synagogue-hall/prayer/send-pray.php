<?php
/**
 * send-pray.php
 * 
 * Capture form request and send a mail
 *
 * @author		Beit Hatfutsot
 * @package		prayer
 * @version		1.0
 */

// get POST variables
$email_to	= (isset($_POST['mail'])	&& $_POST['mail'])	? $_POST['mail']	: '';
$pray_id	= (isset($_POST['id'])		&& $_POST['id'])	? $_POST['id']		: '';

if ( ! $email_to || ! $pray_id )
	// die
	die(999);

// include functions
require_once('functions.php');

// build prayer page link
$protocol	= ( empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off' ) ? 'http://' : 'https://';
$base_url	= $protocol . $_SERVER['SERVER_NAME'] . dirname($_SERVER['PHP_SELF']) . '/';
$link		= $base_url . 'pray.php/?pray_id=' . $pray_id;

// build email fields
$email_from		= 'Beit Hatfutsot <do-not-reply@bh.org.il>';
$email_subject	= 'Beit Hatfutsot - Hallelujah! Assemble, Pray, Study - Synagogues Past and Present';
$email_message	= email_message($link);

// create email headers
$headers	 = "From: " . $email_from . "\r\n";
$headers	.= "Reply-To: ". strip_tags($email_to) . "\r\n";
$headers	.= "MIME-Version: 1.0\r\n";
$headers	.= "Content-Type: text/html; charset=utf-8\r\n";
$headers	.= "X-Mailer: PHP/" . phpversion();

// send mail
$result		= mail( $email_to, $email_subject, $email_message, $headers );
$status		= $result ? 0 : 1;

// die
die($status);