<?php

	// filter past events
	$end_date	= get_field('acf-event_end_date');
	$today		= date_i18n('Ymd');
	$past_event	= ($end_date < $today) ? true : false;
	
	$payed		= get_field('acf-event_payed_event');
	$show_price	= ($payed && !$past_event) ? true : false;	
	
	$series		= get_field('acf-event_series_of_events');
	
	if ($series) :
		$series_price = ($show_price) ? get_field('acf-event_series_price') : '';
		
		echo '<div class="panel panel-default series">';
		
			echo '<div class="panel-heading row">';
				echo '<div class="col-xs-6 col-sm-5 series-title">' . BH_get_event_type($post->ID) . ' \'' . get_the_title() . '\'</div>';
				echo '<div class="col-xs-2 col-sm-2 col-sm-push-2 series-price">' . ( ($series_price) ? $series_price . ' &#8362;' : '' ) . '</div>';
				echo '<div class="col-xs-4 col-sm-3 col-sm-push-2 series-btn">' . ( (!$past_event) ? BH_get_event_purchase_btn($post->ID) : '' ) . '</div>';
			echo '</div>';
			
			echo '<div class="panel-body">';
				
				foreach ($series as $event) :
					$title				= $event['title'];
					$content			= $event['content'];
					$date				= $event['date'];
					$price				= ($show_price) ? $event['price'] : '';
					$ticket_net_link	= ($show_price) ? $event['ticket_net_link'] : '';
					
					if ($date) :
						$formated_date	= date_create_from_format('Ymd', $date);
						$formated_date	= date_format($formated_date, 'd.m.Y');
					endif;
					
					echo '<div class="row event">';
						echo '<div class="' . ( ($show_price) ? 'col-xs-3 col-sm-5' : 'col-xs-6' ) . ' event-title-wrapper">';
							echo '<div class="event-title">' . $title . '</div>';
							echo ($content) ? '<div class="event-content hidden-xs">' . $content . '</div>' : '';
						echo '</div>';
						echo '<div class="' . ( ($show_price) ? 'col-xs-3 col-sm-2' : 'col-xs-6 aligned' ) . ' event-date">' . $formated_date . '</div>';
						
						if ($show_price) :
							echo '<div class="col-xs-2 col-sm-2 event-price">' . ( ($price) ? $price . ' &#8362;' : '' ) . '</div>';
							echo '<div class="col-xs-4 col-sm-3 event-btn">' . ( ($date >= $today && $ticket_net_link) ? '<a class="btn green-btn event-ticket-net-link small" onclick="BH_general.ticketnet_purchase_link(\'' . $ticket_net_link . '\')">' . __('Purchase', 'BH') . '</a>' : '' ) . '</div>';
						endif;
					echo '</div>';
				endforeach;
				
			echo '</div>';
			
			echo '<div class="panel-footer"></div>';
			
		echo '</div>';
	endif;

?>