<?php
/**
 * The Template for displaying single event/post page
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

		if ( is_singular('event') ) :
			$landing_mode = get_field('acf-event_landing_mode');
			($landing_mode) ? get_template_part('views/event/single', 'landing-mode') : get_template_part('views/event/single');
		else :
			get_template_part('views/blog/single');
		endif;

	endif;
	
?>

<?php get_footer(); ?>