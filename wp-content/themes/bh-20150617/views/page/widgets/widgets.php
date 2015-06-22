<?php

	while (has_sub_field('acf-content_page_template_widgets_area')) :
	
		$layout = get_row_layout();
		
		switch ($layout) :
		
			case 'languages' :
			
				/*************/
				/* languages */
				/*************/
				
				get_template_part('views/page/widgets/languages');
				
				break;
				
			case 'content_box' :
			
				/***************/
				/* content_box */
				/***************/
				
				get_template_part('views/page/widgets/content-box');
				
				break;
				
			case 'contacts' :
			
				/************/
				/* contacts */
				/************/
				
				get_template_part('views/page/widgets/contacts');
				
		endswitch;
		
	endwhile;

?>