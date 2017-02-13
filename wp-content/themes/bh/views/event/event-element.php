<?php
/**
 * Event - event element markup
 *
 * @author 		Beit Hatfutsot
 * @package 	bh/views/event
 * @version     2.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

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