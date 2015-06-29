<?php

	define('DOING_AJAX', true);
	define('WP_ADMIN', false);
	header('Cache-Control: no-cache, must-revalidate');
	
	require_once('../../../../../../wp-load.php');
	
	// Setup variables
	$taxonomy			= (isset($_POST['taxonomy'])			&& $_POST['taxonomy'])													? $_POST['taxonomy']			: '';
	$term_id			= (isset($_POST['term_id'])				&& $_POST['term_id'])													? $_POST['term_id']				: '';
	$min_handle_price	= (isset($_POST['min_handle_price'])	&& ( $_POST['min_handle_price'] || $_POST['min_handle_price'] == 0 ))	? $_POST['min_handle_price']	: '';
	$max_handle_price	= (isset($_POST['max_handle_price'])	&& ( $_POST['max_handle_price'] || $_POST['max_handle_price'] == 0 ))	? $_POST['max_handle_price']	: '';
	$taxonomies			= (isset($_POST['taxonomies'])			&& $_POST['taxonomies'])												? $_POST['taxonomies']			: '';
	
	$min_price			= null;
	$max_price			= null;	
	
	$posts				= array();
	$result				= array();
	
	if	( ! $taxonomy || ! $term_id || ! $taxonomies ) :
		echo '999';	// error 999 => General Error
		return;
	endif;
	
	// update filter values and get filtered posts
	$posts = BH_init_product_filter_values( $taxonomy, $term_id, $min_price, $max_price, $min_handle_price, $max_handle_price, $taxonomies );
	
	$result['min_price']		= $min_price;
	$result['max_price']		= $max_price;
	$result['min_handle_price']	= $min_handle_price;
	$result['max_handle_price']	= $max_handle_price;
	$result['taxonomies']		= $taxonomies;
	$result['posts']			= $posts;
	
	echo json_encode($result);
	
?>