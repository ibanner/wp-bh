<?php

	$image		= get_sub_field('map_image');
	$link		= get_sub_field('google_maps_link');
	
	global $sm_row_status, $sm_col_status;
	
	$sm_new_col	= get_sub_field('sm_new_col');

	if ($image) :
	
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
		
		echo '<div class="contact-details-layout contact-details-layout-map">';

			echo ($link) ? '<a href="' . $link . '" target="_blank">' : '';
				echo '<div class="map" style="background-image: url(\'' . $image['url'] . '\');"></div>';
			echo ($link) ? '</a>' : '';
		
		echo '</div>';
	
	endif;

?>