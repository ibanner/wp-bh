<?php
/**
 * The Template for displaying blog archive page
 *
 * @author 		Beit Hatfutsot
 * @package 	bh
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header(); ?>

<?php

	// content
	get_template_part('views/blog/archive');
	
?>

<?php get_footer(); ?>