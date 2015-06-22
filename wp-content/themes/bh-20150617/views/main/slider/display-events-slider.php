<?php

	// layout parameters
	$layout_title				= get_sub_field('layout_title');
	$sort_title					= get_sub_field('sort_title');
	
	global $categories, $is_categories_empty, $is_events_empty;

	if (!$is_events_empty) :

		echo '<section class="main-layout main-layout-slider">';
		
			echo '<div class="container">';
			
				echo ($layout_title) ? '<h2>' . $layout_title . '</h2>' : '';
				
				// display categories filter
				if (!$is_categories_empty) :
					echo '<div class="categories-filter">';
						echo ($sort_title) ? '<span class="sort-title">' . $sort_title . ' </span>' : '';
						echo '<ul>';
							echo '<li category="0" class="active">' . __('All events', 'BH') . '</li>';
							
							foreach ($categories as $key => $val) :
								if ($key != '0' && count($val) > 0) :
									$category = get_term_by('id', $key, 'event_category');
									echo '<li class="delimiter">|</li>';
									echo '<li category="' . $key . '">' . $category->name . '</li>';
								endif;
							endforeach;
						echo '</ul>';
					echo '</div>';
				endif;

				$visible_events = (count($categories[0]) < 6) ? count($categories[0]) : 6;
				
				// display events
				echo '<div class="events-slider-placeholder">';
					echo '<div class="events-slider">';
						echo '<div class="cycle-slideshow"
							data-cycle-slides=".event-item"
							data-cycle-fx=carousel
							data-cycle-timeout=0
							data-cycle-carousel-visible=' . $visible_events . '
							data-cycle-manual-trump=false
							data-cycle-swipe=true
    						data-cycle-log=false
							data-cycle-next="#events-slider-next"
							data-cycle-prev="#events-slider-prev"
							>';
							
							echo implode('', $categories[0]);
							
						echo '</div>';
					echo '</div>';

					echo '<div id="events-slider-next"><i class="fa fa-angle-left"></i></div>';
					echo '<div id="events-slider-prev"><i class="fa fa-angle-right"></i></div>';
				echo '</div>';
				
			echo '</div>';
			
		echo '</section>';
		
		// save $categories in JSON format for filtering use ?>
		<script>
			var _BH_events = <?php echo json_encode($categories); ?>;
		</script>
		
	<?php endif;

?>