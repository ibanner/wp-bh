<?php

	// register widgets
	require_once('widgets/active-trail-newsletter/active-trail-newsletter.php');
	require_once('widgets/shop-refine-products/shop-refine-products.php');
	
	// categories widget - wrap categories post count
	function add_span_cat_count($links) {
		$links = str_replace('</a> (', '</a> <span>(', $links);
		$links = str_replace(')', ')</span>', $links);
		
		return $links;
	}
	add_filter('wp_list_categories', 'add_span_cat_count');

?>