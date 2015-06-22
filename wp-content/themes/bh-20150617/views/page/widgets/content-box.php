<?php

	$content = get_sub_field('content_box');
	
	if ($content) :
	
		echo '<div class="content-box widget">';
			echo $content;
		echo '</div>';
	
	endif;

?>