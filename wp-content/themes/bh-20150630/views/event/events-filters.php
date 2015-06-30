<?php

	global $category_id;
	
	// get event categories
	$args = array(
		'orderby'	=> 'term_order'
	);
	
	if ( function_exists('BH_get_cached_terms') )
		$categories = BH_get_cached_terms('event_category', $args);
	else
		$categories = get_terms('event_category', $args);
	
	echo '<div class="event-filters-outer-wrapper">';
		echo '<div class="event-filters-inner-wrapper">';
		
			echo '<div class="event-filters">';
				echo '<div class="event-filter-by-category">';
					echo '<select>';
						
						echo '<option value="">' . __('All Categories', 'BH') . '</option>';
						foreach ($categories as $cat) :
							if ( function_exists('BH_get_cached_event_category_events') )
								$events_exist = BH_get_cached_event_category_events($cat);
							else
								$events_exist = BH_get_event_category_events($cat->term_id);
								
							echo ($events_exist) ? '<option value="' . $cat->term_id . '"' .( ($category_id == $cat->term_id) ? ' selected=selected' : '' ) . '>' . $cat->name . '</option>' : '';
						endforeach;
						
					echo '</select>';
				echo '</div>';
				
				echo '<div class="event-filter-by-date">';
					echo '<input class="datepicker" value="' . __('Start from', 'BH') . '" />';
				echo '</div>';
				
				echo '<div class="loader">';
					get_template_part('views/components/loader');
				echo '</div>';
			echo '</div>';
			
		echo '</div>';
	echo '</div>';

?>