<?php

	global $events;
	
	// get future events
	$args = array(
		'post_type'			=> 'event',
		'posts_per_page'	=> -1,
		'no_found_rows'		=> true,
		'orderby'			=> 'menu_order',
		'order'				=> (ICL_LANGUAGE_CODE == 'he') ? 'DESC' : 'ASC',
		'meta_query'		=> array(
			'relation'		=> 'AND',
			array(
				'key'		=> 'acf-event_homepage_slider_indicator',
				'value'		=> true
			),
			array(
				'key'		=> 'acf-event_end_date',
				'value'		=> date_i18n('Ymd'),
				'type'		=> 'DATE',
				'compare'	=> '>='
			)
		)
	);
	
	if ( function_exists('BH_get_cached_wp_query') ) :
		$events = BH_get_cached_wp_query($args, 'events-' . ICL_LANGUAGE_CODE);
	else :
		$events = array();
		$query = new WP_Query($args);
		
		if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();
			$events[] = $post;
		endwhile; endif; wp_reset_postdata();
	endif;
	
	if ($events) :
	
		// build event elements
		get_template_part('views/main/slider/set-event-elements');

		// display events slider
		get_template_part('views/main/slider/display-events-slider');
		
	endif;
	
?>