<?php
/**
 * Template Name: Main
 */?>
<?php get_header(); ?>

<?php

	while (has_sub_field('acf-main_template_layouts')) :
	
		$layout = get_row_layout();
		
		switch ($layout) :
		
			case 'banner' :
			
				/**********/
				/* banner */
				/**********/
				
				get_template_part('views/main/layout', 'banner');
				
				break;
				
			case 'slider' :
			
				/**********/
				/* slider */
				/**********/
				
				get_template_part('views/main/layout', 'slider');
				
				break;
				
			case 'featured_links' :
			
				/******************/
				/* featured links */
				/******************/
				
				get_template_part('views/main/layout', 'featured-links');
				
		endswitch;
		
	endwhile;

?>

<?php get_footer(); ?>