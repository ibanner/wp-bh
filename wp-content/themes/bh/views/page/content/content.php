<?php
/**
 * The Template for displaying page layouts content
 *
 * @author		Beit Hatfutsot
 * @package		bh/views/page/content
 * @version		2.1
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

while ( has_sub_field('acf-content_page_template_layouts') ) :

	$layout = get_row_layout();
	
	switch ( $layout ) :
	
		case 'content_row' :
		
			/***************/
			/* content row */
			/***************/
			
			get_template_part( 'views/page/content/content-row' );
			
			break;
			
		case 'faqs' :
		
			/********/
			/* FAQs */
			/********/
			
			get_template_part( 'views/page/content/faqs' );
			
			break;
			
		case 'excerpts' :
		
			/************/
			/* excerpts */
			/************/
			
			get_template_part( 'views/page/content/excerpts' );
			
			break;
			
		case 'contacts' :
		
			/************/
			/* contacts */
			/************/
			
			get_template_part( 'views/page/content/contacts' );

	endswitch;

endwhile;