<?php
/**
 * The Template for displaying default page
 *
 * @author 		Beit Hatfutsot
 * @package 	bh
 * @version     2.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header(); ?>

<?php

	// content
	if (have_posts()) : the_post();
	
		get_template_part('views/page/page');
		
	endif;
	
?>

<?php get_footer(); ?>