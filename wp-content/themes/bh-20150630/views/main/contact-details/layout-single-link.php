<?php

	$icon			= get_sub_field('icon');
	$link			= get_sub_field('link');
	$text			= get_sub_field('text');
	$show_in_mobile	= get_sub_field('show_in_mobile');
	
	global $sm_row_status, $sm_col_status;
	
	$sm_new_col		= get_sub_field('sm_new_col');
	
	if ($icon && $text) :
	
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
		
		echo '<div class="contact-details-layout contact-details-layout-single-link' . ( ($show_in_mobile) ? ' visible-xs' : '' ) . '">';

			echo ($link) ? '<a href="' . $link . '" target="_blank">' : '';
				echo '<div class="icon">';
					echo '<img src="' . $icon['url'] . '" ' . ( ($icon['alt']) ? 'alt="' . $image['alt'] . '"' : '' ) . ' />';
				echo '</div>';
				
				echo ($text) ? '<div class="text">' . $text . '</div>' : '';
			echo ($link) ? '</a>' : '';

		echo '</div>';
	
	endif;

?>