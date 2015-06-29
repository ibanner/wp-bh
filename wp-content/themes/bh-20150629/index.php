<?php
/**
 * The Template for displaying default post/category/archive/page
 *
 * @author 		Beit Hatfutsot
 * @package 	bh
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header(); ?>

<?php
	/**
	 * BH_before_main_content hook
	 *
	 * @hooked BH_theme_wrapper_start - 10 (outputs opening section and divs for the content)
	 */
	do_action('BH_before_main_content');
?>

<?php

	// content
	if (have_posts()) :
	
		while (have_posts()) : the_post();
			get_template_part('views/page/loop');
		endwhile;
		
	else :
	
		get_template_part('views/components/not-found');
		
	endif;
	
?>
	
<?php
	/**
	 * BH_after_main_content hook
	 *
	 * @hooked BH_theme_wrapper_end - 10 (outputs closing section and divs for the content)
	 */
	do_action('BH_after_main_content');
?>

<?php get_footer(); ?>