<?php

	$excerpts = get_sub_field('excerpts_list');
	
	if ($excerpts) :
	
		echo '<ul class="excerpts">';
		
			foreach($excerpts as $e) :
			
				$image		= $e['image'];
				$title		= $e['title'];
				$excerpt	= $e['excerpt'];
				
				echo '<li>';
					echo ($image) ? '<div class="image"><img src="' . $image['url'] . '" alt="' . $title . '"></div>' : '';
					echo '<div class="content ' . ( ($image) ? 'has-image' : '' ) . '">';
						echo '<h3 class="title">' . $title . '</h3>';
						echo $excerpt;
					echo '</div>';
				echo '</li>';
			
			endforeach;
			
		echo '</ul>';
	
	endif;

?>