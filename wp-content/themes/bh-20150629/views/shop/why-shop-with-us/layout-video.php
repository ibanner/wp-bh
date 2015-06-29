<?php

	// layout parameters
	$video_link		= get_sub_field('video_link');
	
	if ($video_link) :
	
		echo '<section class="shop-wswu-layout shop-wswu-layout-video">';
			echo '<div class="container">';
			
				echo ($video_link) ? '<div class="video">' . $video_link . '</div>' : '';
				
			echo '</div>';
		echo '</section>';
		
	endif;

?>