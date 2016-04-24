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
$email_to	= (isset($_POST['mail'])	&& $_POST['mail'])	? $_POST['mail']	: 'nirg@bh.org.il';
$pray_id	= (isset($_POST['id'])		&& $_POST['id'])	? $_POST['id']		: '10';

if ( ! $email_to || ! $pray_id )
	// die
	die(999);

// build prayer page link
$protocol	= ( empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off' ) ? 'http://' : 'https://';
$base_url	= $protocol . $_SERVER['SERVER_NAME'] . dirname($_SERVER['PHP_SELF']) . '/';
$link		= $base_url . 'pray.php/?pray_id=' . $pray_id;

// build email fields
$email_from		= 'Beit Hatfutsot <do-not-reply@bh.org.il>';
$email_subject	= 'Beit Hatfutsot - Synagogue Hall Prayers';
$email_message	= 'Test...';

// create email headers
$headers	=	'From: ' . $email_from . "\r\n" .
				'Reply-To: ' . $email_from . "\r\n" .
				'X-Mailer: PHP/' . phpversion();

// send mail
$result		= mail($email_to, $email_subject, $email_message, $headers);
$status		= $result ? 0 : 1;

// die
die($status);