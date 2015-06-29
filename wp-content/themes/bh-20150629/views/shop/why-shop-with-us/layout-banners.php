<?php

	// layout parameters
	$banners = get_sub_field('banners');
	
	if ($banners) :
	
		echo '<section class="shop-wswu-layout shop-wswu-layout-banners">';
			echo '<div class="container">';
			
				foreach ($banners as $b) :
					$image_size	= $b['image_size'];
					$image		= $b['image'];
					$text		= $b['text'];
					
					if ($image_size && $image && $text) :
						echo '<div class="col-shop-wswu col-shop-wswu-' . $image_size . '">';
							echo '<div class="image-wrapper">';
								echo '<img src="' . $image['url'] . '" alt="' . $image['alt'] . '" width="' . $image['sizes']['large-width'] . '" height="' . $image['sizes']['large-height'] . '" />';
								echo '<div class="image-text">' . $text . '</div>';
							echo '</div>';
						echo '</div>';
					endif;
				endforeach;
				
			echo '</div>';
		echo '</section>';
		
	endif;

?>