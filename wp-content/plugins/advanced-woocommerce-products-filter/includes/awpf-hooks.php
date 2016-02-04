<?php
/**
 * AWPF - hooks
 *
 * @author 		Nir Goldberg
 * @package 	includes
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * awpf_before_main_content
 *
 * @see		awpf_output_widget_title
 */
add_action( 'awpf_before_main_content', 'awpf_output_widget_title', 10, 1 );