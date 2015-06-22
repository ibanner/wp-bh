<?php
/**
 * The Template for displaying search page
 *
 * @author 		Beit Hatfutsot
 * @package 	bh
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header(); ?>

<?php

	// content
	get_template_part('views/page/google-search');
	
?>

<?php get_footer(); ?>