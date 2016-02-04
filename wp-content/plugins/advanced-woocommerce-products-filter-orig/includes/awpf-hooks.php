<?php
/**
 * Advanced WooCommerce Products Filter hooks
 *
 * @author 		Nir Goldberg
 * @package 	advanced-woocommerce-products-filter/includes
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * awpf_init
 *
 * @see		awpf_init_categories
 * @see		awpf_init_taxonomies
 */
add_action( 'awpf_init', 'awpf_init_categories', 10, 2 );
add_action( 'awpf_init', 'awpf_init_taxonomies', 20, 2 );

/**
 * awpf_before_main_content
 *
 * @see		awpf_output_widget_title
 */
add_action( 'awpf_before_main_content', 'awpf_output_widget_title', 10, 1 );