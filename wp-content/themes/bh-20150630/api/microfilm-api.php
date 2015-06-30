<?php

	require_once('../../../../wp-load.php');
	
	if ($_SERVER['REQUEST_METHOD'] === 'GET') {
		
		// first step - display microfilm data, before form submitted
		$results = $wpdb->get_results( 'SELECT * FROM wp_microfilm', OBJECT );
		echo json_encode($results);
		
	} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
		
		// second step - after form submitted
		
		// 'password' is a fake field to prevent bot activity. It should always be empty
		if ( !empty($_POST['password']) ) {
			die();
		}
		
		session_start();
		$_SESSION['microfilm-post'] = $_POST;
		
		if (isset($_SESSION['microfilm-post'])) {
			json_encode(
				array (
					'success' => true
				)
			);
		}
	}
