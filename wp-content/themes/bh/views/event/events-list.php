<?php
/**
 * Event - display events list
 *
 * @author 		Beit Hatfutsot
 * @package 	bh/views/event
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $sorted_events;

if ( is_page() && 'past-events.php' == basename( get_page_template() ) ) {

	if ( $sorted_events['past'] ) :
		echo '<div class="events-list past-events-list">';
			echo implode('', $sorted_events['past']);
		echo '</div>';
	endif;
	
}
else {

	$future_events_title = get_field('acf-options_future_events_list_title', 'option');
	
	if ( $sorted_events['sticky'] || $sorted_events['current'] ) {
		echo '<div class="events-list current-events-list">';
			echo ( $sorted_events['sticky'] ? implode('', $sorted_events['sticky']) : '' );
			echo ( $sorted_events['current'] ? implode('', $sorted_events['current']) : '' );
		echo '</div>';
	}
	
	if ( $sorted_events['future'] ) {
		echo ($future_events_title) ? '<div class="future_events_title"><h3>' . $future_events_title . '</h3></div>' : '';
		
		echo '<div class="events-list future-events-list">';
			echo implode('', $sorted_events['future']);
		echo '</div>';
	}
	
}