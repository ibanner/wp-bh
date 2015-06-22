<?php

	// layout parameters
	$title		= get_sub_field('title');
	$columns	= get_sub_field('columns');
	
	if ($title || $columns) :
	
		echo '<section class="shop-wswu-layout shop-wswu-layout-content">';
			echo '<div class="container">';
			
				echo ($title) ? '<div class="title">' . $title . '</div>' : '';
				
				if ($columns) :
					echo '<div class="row columns">';
					
						foreach ($columns as $c) :
							$icon		= $c['icon'];
							$content	= $c['content'];
							
							if ($icon || $content) :
								echo '<div class="col-sm-4">';
									if ($icon) :
										echo '<div class="column-icon">';
											echo '<img src="' . $icon['url'] . '" alt="' . $icon['alt'] . '" width="' . $icon['sizes']['large-width'] . '" height="' . $icon['sizes']['large-height'] . '" />';
										echo '</div>';
									endif;
									
									echo ($content) ? '<div class="column-content">' . $content . '</div>' : '';
								echo '</div>';
							endif;
						endforeach;
						
					echo '</div>';
				endif;
				
			echo '</div>';
		echo '</section>';
		
	endif;

?>