<?php

	$series_of_events_prepend	= get_field('acf-options_series_of_events_prepend_' . ICL_LANGUAGE_CODE, 'option');
	
	global $categories, $events, $is_categories_empty, $is_events_empty, $sitepress;

	// get locale string
	$locale = $sitepress->get_locale(ICL_LANGUAGE_CODE);

	// set $categories - array of categories term_id
	// this array will hold arrays of event DOM elements related to each category
	// used for two purposes:
	// 1. display categories filter - display only categories which include at least one future event
	// 2. filter events based on a JSON encoded information
	$args = array(
		'orderby'	=> 'term_order'
	);
	
	if ( function_exists('BH_get_cached_terms') )
		$category_terms = BH_get_cached_terms('event_category', $args);
	else
		$category_terms = get_terms('event_category', $args);
	
	$categories = array();
	$is_categories_empty	= true;	// indicates there is no category includes at least 1 future event
	$is_events_empty		= true;	// indicates there is no events to show

	// set $categories[0] for all events
	$categories[0] = array();
	
	if ($category_terms) :
		foreach ($category_terms as $category_term) :
			// set empty array to each category as default
			$categories[$category_term->term_id] = array();
		endforeach;
	endif;
	
	// fill in $categories array
	foreach ($events as $event) :
		$image = get_field('acf-event_slider_image', $event->ID);
		
		if ($image) :
			$event_categories	= wp_get_post_terms($event->ID, 'event_category');
			$singular_name		= ($event_categories) ? get_field('acf-event_category_singular_name',				'event_category_' . $event_categories[0]->term_id) : '';
			$description		= get_field('acf-event_description',		$event->ID);
			$series				= get_field('acf-event_series_of_events',	$event->ID);
			
			$event_element =
				"<div class='event-item event-item-" . $event->ID . "' style='display: none;'>" .
					"<a href='" . get_permalink($event->ID) . "'>" .
						"<img src='" . $image['sizes']['thumbnail'] . "' alt='" . ( ($image['alt']) ? $image['alt'] : '' ) . "' />" .
						"<div class='event-meta'>" .
							
							// event type
							BH_get_event_type($event->ID) .
							
							// event date
							BH_get_event_date($event->ID) .
							
							// event title and description
							"<h3>" . get_the_title($event->ID) . "</h3>" .
							// "<div class='event-desc'>" . $description . "</div>" .
						"</div>" .
					"</a>" .
				"</div>";
			
			// include event as part of "all events" array
			$categories[0][] = $event_element;
			
			// include event as part of its categories arrays
			if ($event_categories) :
				foreach ($event_categories as $event_category) :
					$categories[$event_category->term_id][] = $event_element;
				endforeach;

				$is_categories_empty = false;	// at least 1 category includes at least 1 future event
			endif;
			
			$is_events_empty = false;	// at least 1 event to show
		endif;
	endforeach;

?>