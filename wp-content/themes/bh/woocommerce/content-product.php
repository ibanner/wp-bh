<?php
/**
 * The template for displaying product content within loops.
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product.php
 *
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product, $featured_product;

// Ensure visibility
if ( ! $product || ! $product->is_visible() ) {
	return;
}

// Extra post classes
$classes = array();
if ( $featured_product ) {
	$classes[] = 'featured-product-item';
}

// product item
echo '<li ';
	echo 'data-postid=' . $post->ID . ' ';
	post_class( $classes );
	echo '>';

	get_template_part('views/woocommerce/product-item');
echo '</li>';