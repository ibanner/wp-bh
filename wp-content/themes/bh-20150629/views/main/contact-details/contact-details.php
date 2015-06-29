<?php

	global $sm_row_status, $sm_col_status;

	$sm_row_status = 'close';	// [open/close]
	$sm_col_status = 'close';	// [open/close]
	
	while (has_sub_field('acf-options_contact_details', 'option')) :
	
		$layout = get_row_layout();
		
		switch ($layout) :
		
			case 'text_box' :
			
				/************/
				/* text box */
				/************/
				
				get_template_part('views/main/contact-details/layout', 'text-box');
				
				break;
				
			case 'map' :
			
				/*******/
				/* map */
				/*******/
				
				get_template_part('views/main/contact-details/layout', 'map');
				
				break;
				
			case 'single_link' :
			
				/***************/
				/* single link */
				/***************/
				
				get_template_part('views/main/contact-details/layout', 'single-link');
				
				break;
				
			case 'multiple_links' :
			
				/******************/
				/* multiple links */
				/******************/
				
				get_template_part('views/main/contact-details/layout', 'multiple-links');
				
				break;
				
			case 'opening_hours_message' :
			
				/*************************/
				/* opening hours message */
				/*************************/
				
				get_template_part('views/main/contact-details/layout', 'opening-hours-message');
				
		endswitch;
		
	endwhile;
	
	// sm resolution - close last col
	echo ($sm_col_status == 'open') ? '</div><!-- contact col -->' : '';

	// sm resolution - close last row
	echo ($sm_row_status == 'open') ? '</div><!-- contact row -->' : '';

?>