<?php

	define('DOING_AJAX', true);
	define('WP_ADMIN', false);
	header('Cache-Control: no-cache, must-revalidate');
	
	require_once('../../../../../../wp-load.php');
	
	// Setup variables
	$data = (isset($_POST['widget-active_trail_newsletter']) && $_POST['widget-active_trail_newsletter']) ? $_POST['widget-active_trail_newsletter'] : '';
	
	// collect data
	if ($data) :
		foreach ($data as $value) :
			$user_id	= (isset($value['mm_userid']) && $value['mm_userid']) ? $value['mm_userid'] : '';
			$mail		= (isset($value['mm_newemail']) && $value['mm_newemail']) ? $value['mm_newemail'] : '';
			$groups		= (isset($value['mm_key']) && $value['mm_key']) ? $value['mm_key'] : '';
		endforeach;
	endif;
	
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