<?php

	define('DOING_AJAX', true);
	define('WP_ADMIN', false);
	header('Cache-Control: no-cache, must-revalidate');
	
	require_once('../../../../../../wp-load.php');
	
	// collect data
	$user_id	= (isset($_POST['mm_userid']) && $_POST['mm_userid']) ? $_POST['mm_userid'] : '';
	$mail		= (isset($_POST['mm_newemail']) && $_POST['mm_newemail']) ? $_POST['mm_newemail'] : '';
	$groups		= (isset($_POST['mm_key']) && $_POST['mm_key']) ? $_POST['mm_key'] : '';

	// registration requests
	if (!$user_id || !$mail || !$groups) :
		echo '999';	// error 999 => General Error
		return;
	endif;
	
	$result = array();
	
	foreach ($groups as $group) :
	
		$url		= 'https://webapi.mymarketing.co.il/Signup/PerformOptIn.aspx';
		$postData	= array (
			'mm_userid'		=> $user_id,
			'mm_newemail'	=> $mail,
			'mm_key'		=> $group,
			'mm_culture'	=> 'he'
		);
		
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
		
		// execute request and save result
		$result[$group] = curl_exec($ch);
		
		curl_close($ch);
		
	endforeach;
	
	// check request results
	if (!$result) :
		echo '1';	// error 1 => at least one process has failed
		return;
	endif;
	
	$res = '0';
	foreach ($result as $group_res)
		if (!$group_res) :
			$res = '1';
			break;
		endif;
	
	echo $res;

?>