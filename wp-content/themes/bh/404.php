<?php
/**
 * The Template for displaying 404 page
 *
 * @author 		Beit Hatfutsot
 * @package 	bh
 * @version     2.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header(); ?>

<?php

	// content
	get_template_part('views/page/404');
	
?>

<?php get_footer(); ?>