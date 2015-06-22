<?php
/**
 * BH theme support
 *
 * @author 		Beit Hatfutsot
 * @package 	bh/functions
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action('BH_before_main_content', 'BH_theme_wrapper_start', 10);
add_action('BH_after_main_content', 'BH_theme_wrapper_end', 10);

function BH_theme_wrapper_start() {
	echo '<section class="page-content"><div class="container"><div class="row">';
}

function BH_theme_wrapper_end() {
	echo '</div></div></section>';
}

// theme supports
add_theme_support('menus');
add_theme_support('post-thumbnails');

// remove WP defaults
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');