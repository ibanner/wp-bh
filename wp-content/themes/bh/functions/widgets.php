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

/**
 * BH_newsletter_widget
 *
 * @return		String		newsletter button
 */
function BH_newsletter_widget() {
	$output = '';

	if ( is_active_sidebar('newsletter-top-menu') ) :

		$output .= '<div class="newsletter-widget-btn">';
			$output .= '<button class="label">' . __('ENews', 'BH') . '</button>';
		$output .= '</div>';

		$output .= '<div class="newsletter-widget-content">';
			$output .= BH_get_dynamic_sidebar('newsletter-top-menu');
			$output .= '<span class="glyphicon glyphicon-remove"></span>';
		$output .= '</div>';

	endif;

	return $output;
}

// Categories widget - wrap categories post count
function add_span_cat_count($links) {
	$links = str_replace('</a> (', '</a> <span>(', $links);
	$links = str_replace(')', ')</span>', $links);
	
	return $links;
}
add_filter('wp_list_categories', 'add_span_cat_count');