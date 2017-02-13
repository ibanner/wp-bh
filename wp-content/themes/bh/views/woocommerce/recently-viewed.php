<?php
/**
 * recently-viewed
 *
 * Contains the markup used by the Woocommerce Recently Viewed widget
 *
 * @author 		Beit Hatfutsot
 * @package 	bh/views/woocommerce
 * @version     2.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$viewed_products = ! empty( $_COOKIE['woocommerce_recently_viewed'] ) ? (array) explode( '|', $_COOKIE['woocommerce_recently_viewed'] ) : array();
$viewed_products = array_filter( array_map( 'absint', $viewed_products ) );

if ( empty($viewed_products) )
	return;
	
$query_args	= array(
	'post_type'			=> 'product',
	'posts_per_page'	=> -1,
	'no_found_rows'		=> true,
	'post__in'			=> $viewed_products,
	'orderby'			=> 'post__in',
	'order'				=> 'ASC'
);

$query_args['meta_query'] = array();
$query_args['meta_query'][] = WC()->query->stock_status_meta_query();
$query_args['meta_query'] = array_filter( $query_args['meta_query'] );

$r = new WP_Query($query_args);

if ($r->have_posts()) : while ($r->have_posts()) : $r->the_post();
	wc_get_template('content-widget-product.php');
endwhile; endif; wp_reset_postdata();