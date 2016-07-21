<?php

	/**
	 * check if an event category is empty - only current and future events are counted
	 * 
	 * false	=> empty
	 * array	=> not empty category - return array of event objects
	 * 
	 * input:	category ID
	 * output:	boolean/array
	 */
	function BH_get_event_category_events($cat_id) {
		global $post;
		$result = false;

		$args = array(
			'post_type'			=> 'event',
			'posts_per_page'	=> -1,
			'no_found_rows'		=> true,
			'meta_key'			=> 'acf-event_end_date',
			'orderby'			=> 'meta_value',
			'order'				=> 'ASC',
			'tax_query'			=> array(
				array(
					'taxonomy'	=> 'event_category',
					'field'		=> 'id',
					'terms'		=> (int)$cat_id
				)
			),
			'meta_query'		=> array(
				array(
					'key'		=> 'acf-event_end_date',
					'value'		=> date_i18n('Ymd'),
					'type'		=> 'DATE',
					'compare'	=> '>='
				)
			)
		);
		$events_query = new WP_Query($args);
		
		if ( $events_query->have_posts() ) :
			$result = array();
			
			while ( $events_query->have_posts() ) : $events_query->the_post();
				$result[] = $post;
			endwhile;
		endif;
		
		wp_reset_postdata();
		
		return $result;
	}
	
	/**
	 * BH_get_event_date
	 * 
	 * get event date HTML string
	 * 
	 * @param	int		$event_id	event ID
	 * @param	string	$locale		locale string
	 * @return	HTML string
	 */
	function BH_get_event_date($event_id, $locale = null) {
		$end_date_prepend	= get_field('acf-options_event_end_date_prepend', 'option');
		
		$event_categories	= wp_get_post_terms($event_id, 'event_category');
		$next_event_title	= ($event_categories) ? get_field('acf-event_category_next_event',	'event_category_' . $event_categories[0]->term_id) : '';
		$start_date			= get_field('acf-event_start_date',			$event_id);
		$end_date			= get_field('acf-event_end_date',			$event_id);
		$series				= get_field('acf-event_series_of_events',	$event_id);
		
		$today				= date_i18n('Ymd');
		
		if ( isset($locale) )
			setlocale(LC_TIME, $locale . '.utf8');
		else
			setlocale(LC_TIME, get_locale() . '.utf8');
		
		// get next series event date
		if ($today <= $end_date && $series) :
			$next_series_event = '';
			$next_series_event_date = '';
			
			foreach ($series as $single_series_event) :
				$date = $single_series_event['date'];
				
				if ($date >= $today) :
					$next_series_event_date		= strftime( '%B %d', strtotime($date) );
					break;
				endif;
			endforeach;
		endif;

		// get event start date
		if ($start_date) 
			$s_date = strftime( '%B %d', strtotime($start_date) );
		
		// get event end date
		// assumption: end date must exists since the queries contains only events with future end date
		$e_date		= strftime( '%B %d', strtotime($end_date) );
		
		$date_html =
			"<div class='event-date'>" .
				( ($today <= $end_date)
					?
						// future event
						( ($series && $next_series_event_date && $next_event_title)
							// series
							? $next_event_title . $next_series_event_date
							:
								// one time event
								( ($start_date && $start_date >= $today)
									?
										// start date occurs at the future
										( ($start_date == $end_date)
											?
												// one day period event
												$s_date
											:
												// more than one day period event
												$s_date . ' - ' . $e_date
										)
									:
										// there is no start date (like in Core Exhibition) or start date occured at the past
										( ($start_date)
											?
												// start date occured at the past
												( ($end_date_prepend) ? $end_date_prepend . ' ' : '' ) . $e_date
											:	// no start date defined
												""
										)
								)
						)
					:
						// past event
						( ($start_date)
							?
								// there is start date
								( ($start_date == $end_date)
									?
										// one day period event
										mysql2date('F, \'y', $start_date)
									:
										// more than one day period event
										mysql2date('F, \'y', $start_date) . ' - ' . mysql2date('F, \'y', $end_date)
								)
							:
								// there is no start date
								mysql2date('F, \'y', $end_date)
						)
				) .
			"</div>";
			
		return $date_html;
	}

	/**
	 * get event type
	 * 
	 * input:	event ID
	 * output:	HTML string
	 */
	function BH_get_event_type($event_id) {
		$series_of_events_prepend	= get_field('acf-options_series_of_events_prepend', 'option');
		$event_categories			= wp_get_post_terms($event_id, 'event_category');
		$singular_name				= ($event_categories) ? get_field('acf-event_category_singular_name', 'event_category_' . $event_categories[0]->term_id) : '';
		$series						= get_field('acf-event_series_of_events', $event_id);
		
		$event_type					= '';
		
		if ($event_categories && $series && $series_of_events_prepend) :
			$event_type = '<div class="event-type">' . $series_of_events_prepend . ' ' . $event_categories[0]->name . '</div>';
		elseif ($singular_name) :
			$event_type = '<div class="event-type">' . $singular_name . '</div>';
		endif;
		
		return $event_type;
	}

	/**
	 * display event buttons for a current or future event based on the following options:
	 * 
	 * 1. payed event:
	 * 		1.1 regular event which has a ticket net link - display "Order Tickets" button with the general ticket net link
	 * 		1.2 series of events:
	 * 			1.2.1 the series haven't started yet and there is a general ticket net link - display "Purchase Series" button with the general ticket net link
	 * 			1.2.2 the series already started and there is a future single event in the series defined with a ticket net link - display "Order Tickets" button with this particular event's ticket net link
	 * 2. not payed event:
	 * 		2.1 Event has a registration form indicator and there is a reservation form page - display "Reservation" button with the reservation form page link
	 * 		2.2 Event has no registration form indicator or there is no reservation form link - display "Free Admittance" message
	 * 
	 * note: do not call this function for past events
	 * 
	 * input:	event ID
	 * output:	HTML string
	 */
	function BH_get_event_purchase_btn($event_id) {
		$landing					= get_field('acf-event_landing_mode',		$event_id);
		$payed						= get_field('acf-event_payed_event',		$event_id);
		$start_date					= get_field('acf-event_start_date',			$event_id);
		$end_date					= get_field('acf-event_end_date',			$event_id);
		$series						= get_field('acf-event_series_of_events',	$event_id);
		$ticket_net_link			= get_field('acf-event_ticket_net_link',	$event_id);
		$registration_form			= get_field('acf-event_registration_form',	$event_id);
		
		$order_tickets_btn			= get_field('acf-options_event_btn_order_tickets',		'option');
		$purchase_series_btn		= get_field('acf-options_event_btn_purchase_series',	'option');
		$reservation_btn			= get_field('acf-options_event_btn_reservation',		'option');
		$free_admittance_msg		= get_field('acf-options_event_btn_free_admittance',	'option');
		
		$reservation_form_page		= get_field('acf-options_events_reservation_form_page',	'option');
		$reservation_form_page_id	= $reservation_form_page ? $reservation_form_page->ID : '';
		
		$today						= date_i18n('Ymd');
		$btn						= '';
		
		if ($landing)
			return '';
		
		if ($payed) :
		
			// payed event
			if (!$series) :
			
				// regular event
				if ($ticket_net_link) :
					$btn = '<a class="btn green-btn event-ticket-net-link" href="' . $ticket_net_link . '" target="_blank">' . $order_tickets_btn . '</a>';
				endif;
				
			else :
			
				// series of events
				if ($ticket_net_link && $today <= $start_date) :
					$btn = '<a class="btn green-btn event-ticket-net-link" href="' . $ticket_net_link . '" target="_blank">' . $purchase_series_btn . '</a>';
				else :
					foreach ($series as $event) :
						$event_date				= $event['date'];
						$event_ticket_net_link	= $event['ticket_net_link'];
						
						if ($today <= $event_date && $event_ticket_net_link) :
							$btn = '<a class="btn green-btn event-ticket-net-link" href="' . $event_ticket_net_link . '" target="_blank">' . $order_tickets_btn . '</a>';
							break;
						endif;
					endforeach;
				endif;
				
			endif;
			
		else :
		
			// not payed event
			if ($registration_form && $reservation_form_page_id) :
				$btn = '<a class="btn green-btn event-reservation-form-link" href="' . get_permalink($reservation_form_page_id) . '/?event_id=' . $event_id . '">' . $reservation_btn . '</a>';
			else :
				$btn = '<span class="free_admittance">' . $free_admittance_msg . '</span>';
			endif;			
			
		endif;
		
		return $btn;
	}

?>