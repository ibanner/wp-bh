<?php
/**
 * Widgets
 *
 * Register custom widgets and define related functions
 *
 * @author 		Beit Hatfutsot
 * @package 	bh/functions
 * @version     2.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Register widgets
require_once('widgets/active-trail-newsletter/active-trail-newsletter.php');
require_once('widgets/shop-refine-products/shop-refine-products.php');

// Categories widget - wrap categories post count
function add_span_cat_count($links) {
	$links = str_replace('</a> (', '</a> <span>(', $links);
	$links = str_replace(')', ')</span>', $links);
	
	return $links;
}
add_filter('wp_list_categories', 'add_span_cat_count');