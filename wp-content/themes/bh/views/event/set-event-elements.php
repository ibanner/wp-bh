<?php
/**
 * Event - event elements builder
 *
 * @author 		Beit Hatfutsot
 * @package 	bh/views/event
 * @version     2.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $sticky_events_ids, $events, $sorted_events, $lang;

$locale = $wpdb->get_var("SELECT default_locale FROM {$wpdb->prefix}icl_languages WHERE code='{$lang}'");

$series_of_events_prepend	= get_field('acf-options_series_of_events_prepend',	'option');
$read_more_btn				= get_field('acf-options_event_btn_read_more',		'option');

$today			= date_i18n('Ymd');
$filtered_date	= (isset($_POST['event_date']) && $_POST['event_date']) ? date_create_from_format('d/m/Y', $_POST['event_date'])->format('Ymd') : '';

// Set $sorted_events - array of event elements seperated into sticky, current, future and past events
$sorted_events = array(
	'sticky'	=> array(),
	'current'	=> array(),
	'future'	=> array(),
	'past'		=> array()
);

if ( $events ) {

	// Fill in $sorted_events array
	foreach ($events as $event) {
		$start_date			= get_field('acf-event_start_date',			$event->ID);
		$end_date			= get_field('acf-event_end_date',			$event->ID);
		$image				= get_field('acf-event_slider_image',		$event->ID);
		$description		= get_field('acf-event_description',		$event->ID);
		$series				= get_field('acf-event_series_of_events',	$event->ID);

		$event_categories	= wp_get_post_terms($event->ID, 'event_category');
		$singular_name		= ($event_categories) ? get_field('acf-event_category_singular_name', 'event_category_' . $event_categories[0]->term_id) : '';

		if ( $sticky_events_ids && in_array($event->ID, $sticky_events_ids) ) {
			// Sticky event
			$when = 'sticky';
		}
		elseif ($end_date < $today) {
			// Past event
			$when = 'past';
		}
		else {
			// Current/future event
			if ($filtered_date) {
				// Filtered date
				$when = ($start_date <= $filtered_date) ? 'current' : 'future';
			}
			else {
				// As of today
				$when = ($start_date <= $today) ? 'current' : 'future';
			}
		}
		
		$event_date = BH_get_event_date($event->ID, $locale);

		// Build event element
		include( locate_template('views/event/event-element.php') );

		// Insert event into $sorted_events arrays accordingly
		$sorted_events[$when][] = $event_element;
	}
	
}