<?php

	$type			= get_sub_field('type');
	$title			= get_sub_field('title');
	$content		= get_sub_field('content');
	
	global $sm_row_status, $sm_col_status;
	
	$sm_new_col		= ($type == 'sub') ? get_sub_field('sm_new_col') : '';
	$sm_cell_height	= ($type == 'sub') ? get_sub_field('sm_cell_height') : '';
	
	// special treatment for main text box as it should be displayed in a separate row
	if ($type == 'main') :
	
		// sm resolution - close last col and last row
		echo ($sm_col_status == 'open') ? '</div><!-- contact col --></div><!-- contact row -->' : '';
		$sm_col_status = 'close';
		$sm_row_status = 'close';

		// sm resolution - open a new row
		echo '<div class="row"><div class="col-sm-12">';
		
	else :	// sub text box
		
		// sm resolution - open a new row 
		if ($sm_row_status == 'close') :
			echo '<div class="row">';
			$sm_row_status = 'open';
		endif;
		
		if ($sm_new_col) :
			// sm resolution - close last col
			echo ($sm_col_status == 'open') ? '</div><!-- contact col -->' : '';
			
			// sm resolution - open a new col
			echo '<div class="col-sm-4 col-md-12 col-lg-12">';
			$sm_col_status = 'open';
		endif;
		
	endif;
	
	echo '<div class="contact-details-layout contact-details-layout-text-box"' . ( ($sm_cell_height) ? ' style="height: ' . $sm_cell_height . 'px;"' : '' ) . '>';
	
		if ($title) :
			echo ($type == 'main') ? '<h2>' : '<h3>';
				echo $title;
			echo ($type == 'main') ? '</h2>' : '</h3>';
		endif;
		
		echo ($content) ? $content : '';
	
	echo '</div>';
	
	// special treatment for main text box as it should be displayed in a separate row
	echo ($type == 'main') ? '</div><!-- contact col --></div><!-- contact row -->' : '';

?>