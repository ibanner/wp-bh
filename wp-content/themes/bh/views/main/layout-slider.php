<?php
/**
 * Main - Slider layout
 *
 * @author 		Beit Hatfutsot
 * @package 	bh/views/main
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// check for ACF existence
if ( ! function_exists(get_field) )
	return;

// layout parameters
$slider = get_field('acf-options_exhibitions_and_events_slider', 'option');

if ( ! $slider )
	return;

global $events;
$events = array();

$wpml_lang = function_exists('icl_object_id') ? ICL_LANGUAGE_CODE : '';

// get slider events
foreach ($slider as $s) {
	$event = $s['event'];

	if ( $event ) {
		if ( $wpml_lang == 'he' ) {
			array_unshift($events, $event);
		}
		else {
			$events[] = $event;
		}
	}
}

if ($events) {

	// build event elements
	get_template_part('views/main/slider/set-event-elements');

	// display events slider
	get_template_part('views/main/slider/display-events-slider');
	
}