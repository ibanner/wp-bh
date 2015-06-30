<?php

	$links			= get_sub_field('links');

	global $sm_row_status, $sm_col_status;
	
	$sm_new_col		= get_sub_field('sm_new_col');
	$sm_cell_height	= get_sub_field('sm_cell_height');
	
	if ($links) :
	
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
		
		echo '<div class="contact-details-layout contact-details-layout-multiple-links"' . ( ($sm_cell_height) ? ' style="height: ' . $sm_cell_height . 'px;"' : '' ) . '>';
		
			echo '<ul>';
			
				foreach ($links as $l) :
				
					$icon	= $l['icon'];
					$link	= $l['link'];
					$color	= $l['color'];
					
					if ($icon && $link) :
						echo '<li><a href="' . $link . '" target="_blank"><i class="fa ' . $icon . '" ' . ( ($color) ? 'style="color: ' . $color . ';"' : '' ) . '></i></a></li>';
					endif;
				
				endforeach;
			
			echo '</ul>';
		
		echo '</div>';
	
	endif;

?>