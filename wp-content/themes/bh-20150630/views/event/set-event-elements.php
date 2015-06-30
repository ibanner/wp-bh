<?php

	global $events, $sorted_events, $sitepress, $wpdb;
	
	$icl_language_code = ICL_LANGUAGE_CODE;
	
	if ( ICL_LANGUAGE_CODE == '' && isset($_COOKIE['_icl_current_language']) && defined('ICL_SITEPRESS_VERSION') ) {
		$sitepress->switch_lang( $_COOKIE['_icl_current_language'] );
		$icl_language_code = $_COOKIE['_icl_current_language'];
	}
	
	$locale = $wpdb->get_var("SELECT default_locale FROM {$wpdb->prefix}icl_languages WHERE code='{$icl_language_code}'");
	
	$series_of_events_prepend	= get_field('acf-options_series_of_events_prepend',	'option');
	$read_more_btn				= get_field('acf-options_event_btn_read_more',		'option');
	
	$today			= date_i18n('Ymd');
	$filtered_date	= (isset($_POST['event_date']) && $_POST['event_date']) ? date_create_from_format('d/m/Y', $_POST['event_date'])->format('Ymd') : '';
	
	// set $sorted_events - array of event elements seperated into current, future and past events
	$sorted_events = array(
		'current'	=> array(),
		'future'	=> array(),
		'past'		=> array()
	);
	
	if ($events) :
	
		// fill in $sorted_events array
		foreach ($events as $event) :
			$start_date			= get_field('acf-event_start_date',			$event->ID);
			$end_date			= get_field('acf-event_end_date',			$event->ID);
			$image				= get_field('acf-event_slider_image',		$event->ID);
			$description		= get_field('acf-event_description',		$event->ID);
			$series				= get_field('acf-event_series_of_events',	$event->ID);

			$event_categories	= wp_get_post_terms($event->ID, 'event_category');
			$singular_name		= ($event_categories) ? get_field('acf-event_category_singular_name', 'event_category_' . $event_categories[0]->term_id) : '';

			if ($end_date < $today) :
				// past event
				$when = 'past';
			else :
				// current/future event
				if ($filtered_date) :
					// filtered date
					$when = ($start_date <= $filtered_date) ? 'current' : 'future';
				else :
					// as of today
					$when = ($start_date <= $today) ? 'current' : 'future';
				endif;
			endif;
			
			$event_date = BH_get_event_date($event->ID, $locale);

			$event_element =
				"<div class='row'>" .
					// first column - event name and date
					"<div class='col-md-3 visible-md visible-lg col-1'>" .
						"<h2 class='event-name'><a href='" . get_permalink($event->ID) . "'>" . get_the_title($event->ID) . "</a></h2>" .
						$event_date .
					"</div>" .
					
					// second column - event image
					"<div class='col-xs-6 col-sm-4 col-md-3 col-2'>" .
						( ($image) ? "<a href='" . get_permalink($event->ID) . "'><img src='" . $image['sizes']['thumbnail'] . "' alt='" . ( ($image['alt']) ? $image['alt'] : '' ) . "' /></a>" : "" ) .
					"</div>" .
					
					// third column - event description and buttons
					"<div class='col-xs-6 col-sm-8 col-md-6 col-3'>" .
						"<div class='event-excerpt'>" .
							// event name and date only for small screen size
							"<div class='visible-xs visible-sm'>" .
								"<h2 class='event-name'><a href='" . get_permalink($event->ID) . "'>" . get_the_title($event->ID) . "</a></h2>" .
								$event_date .
							"</div>" .
							
							// event description
							"<div class='event-desc'>" . $description . "</div>" .
						"</div>" .
	
						// event buttons
						"<div class='event-btn'>" .
							// display purchase button only for current/future events
							( ($when != 'past') ? BH_get_event_purchase_btn($event->ID) : '' ) .
							"<a href='" . get_permalink($event->ID) . "'><div class='btn white-btn small event-more'>" . $read_more_btn . "</div></a>" .
						"</div>" .
					"</div>" .
					
					// event type
					BH_get_event_type($event->ID) .
				"</div>";
				
			// insert event into $sorted_events arrays accordingly
			$sorted_events[$when][] = $event_element;
		endforeach;
		
	endif;

?>