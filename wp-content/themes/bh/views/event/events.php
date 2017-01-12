<?php
/**
 * Event - event page template content
 *
 * @author 		Beit Hatfutsot
 * @package 	bh/views/event
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $sticky_events_ids, $events, $category_id;
$events = array();

// Get sticky events
$sticky_events		= get_field('acf-options_exhibitions_and_events_sticky_events', 'option');
$sticky_events_ids	= array();

if ( $sticky_events ) {
	// Exclude events not in the current category
	if ( $category_id ) {
		foreach ( $sticky_events as $key => $s ) {
			$event_categories = wp_get_post_terms( $s['event']->ID, 'event_category', array('fields' => 'ids') );

			if ( ! in_array($category_id, $event_categories) )
				unset( $sticky_events[$key] );
		}
	}

	// Initiate $sticky_events_ids
	// Initiate $events with $sticky_events if exists any
	if ( $sticky_events ) {
		foreach ( $sticky_events as $s ) {
			$sticky_events_ids[] = $s['event']->ID;
			$events[] = $s['event'];
		}
	}
}

// Get future events
$args = array(
	'post_type'			=> 'event',
	'posts_per_page'	=> -1,
	'no_found_rows'		=> true,
	'meta_key'			=> 'acf-event_end_date',
	'orderby'			=> 'meta_value',
	'order'				=> 'ASC',
	'meta_query'		=> array(
		array(
			'key'		=> 'acf-event_end_date',
			'value'		=> date_i18n('Ymd'),
			'type'		=> 'DATE',
			'compare'	=> '>='
		)
	)
);

if ( $category_id ) {
	$args['tax_query'] = array(
		array(
			'taxonomy'	=> 'event_category',
			'field'		=> 'term_id',
			'terms'		=> $category_id
		)
	);
}

if ( $sticky_events_ids ) {
	$args['post__not_in'] = $sticky_events_ids;
}

$events_query = new WP_Query($args);

if ( $events_query->have_posts() ) : 
	while ( $events_query->have_posts() ) : $events_query->the_post();
		$events[] = $post;
	endwhile;
endif;

wp_reset_postdata();

// Build event elements
get_template_part('views/event/set-event-elements');

// Display events filters
get_template_part('views/event/events', 'filters');

// Display events list
echo '<div class="events-list-container">';
	if ($events) :
		get_template_part('views/event/events', 'list');
	else :
		get_template_part('views/components/not-found');
	endif;
echo '</div>';