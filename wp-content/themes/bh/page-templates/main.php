<?php
/**
 * Template Name: Main
 */?>
<?php get_header(); ?>

<div class="main-wrapper">

<?php

	if (get_field('acf-main_template_layouts')) :

		echo '<div class="main-layouts">';

			while (has_sub_field('acf-main_template_layouts')) :
			
				$layout = get_row_layout();
				
				switch ($layout) :
				
					case 'strip' :
					
						/*********/
						/* strip */
						/*********/
						
						get_template_part('views/main/layout', 'strip');
						
						break;
						
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

		echo '</div>';

	endif;

?>

</div>

<?php get_footer(); ?>