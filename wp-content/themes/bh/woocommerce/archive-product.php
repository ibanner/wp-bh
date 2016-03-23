<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive.
 *
 * Override this template by copying it to yourtheme/woocommerce/archive-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header(); ?>

<?php
	/**
	 * woocommerce_before_main_content hook
	 *
	 * @hooked	woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
	 */
	do_action('woocommerce_before_main_content');
?>

<?php
	if (isset($_GET['post_type']) && $_GET['post_type'] == 'product')
		// search products
		get_template_part('views/woocommerce/archive/archive-product', 'search');
		
	elseif ( is_shop() )
		// shop homepage
		get_template_part('views/woocommerce/archive/archive-product', 'home');
		
	else
		// product category archive
		get_template_part('views/woocommerce/archive/archive-product', 'taxonomy-term');
?>

<?php
	/**
	 * woocommerce_after_main_content hook
	 *
	 * @hooked	woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
	 */
	do_action('woocommerce_after_main_content');
?>

<?php get_footer(); ?>