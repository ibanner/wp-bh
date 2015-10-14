<?php

	$image	= get_sub_field('image');
	$link	= get_sub_field('link');

	if ( ! $image )
		return;
	
	echo '<section class="main-layout main-layout-strip">';
	
		echo '<div class="container">';

			echo '<div class="strip">';
				echo $link ? '<a href="' . $link . '">' : '';
					echo '<img src="' . $image['url'] . '" width="' . $image['width'] . '" height="' . $image['height'] . '" alt="' . $image['alt'] . '" />';
				echo $link ? '</a>' : '';
			echo '</div>';

		echo '</div>';
		
	echo '</section>';

?>