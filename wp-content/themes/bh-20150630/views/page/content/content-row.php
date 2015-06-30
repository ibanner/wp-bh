<?php

	$content_columns = get_sub_field('content_columns');
	
	if ($content_columns) :
	
		echo '<div class="row">';
		
			foreach($content_columns as $col) :
			
				$width		= $col['width'];
				$title		= $col['title'];
				$content	= $col['content'];
				
				echo '<div class="col-sm-' . $width . ' content-column">';
					echo ($title) ? '<h2 class="title">' . $title . '</h2><hr />' : '';
					echo $content;
				echo '</div>';
			
			endforeach;
			
		echo '</div>';
	
	endif;

?>