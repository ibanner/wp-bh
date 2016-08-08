<?php

	global $events, $category_id;
	$events = array();
	
	// get future events
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
	
	if ($category_id) :
		$args['tax_query'] = array(
			array(
				'taxonomy'	=> 'event_category',
				'field'		=> 'term_id',
				'terms'		=> $category_id
			)
		);
	endif;
	
	$events_query = new WP_Query($args);
	
	if ( $events_query->have_posts() ) : 
		while ( $events_query->have_posts() ) : $events_query->the_post();
			$events[] = $post;
		endwhile;
	endif;
	
	wp_reset_postdata();
	
	// build event elements
	get_template_part('views/event/set-event-elements');
	
	// display events filters
	get_template_part('views/event/events', 'filters');

	// display events list
	echo '<div class="events-list-container">';
		if ($events) :
			get_template_part('views/event/events', 'list');
		else :
			get_template_part('views/components/not-found');
		endif;
	echo '</div>';

?>