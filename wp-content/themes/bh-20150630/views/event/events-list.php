<?php

	global $sorted_events, $sitepress;
	
	if ( is_page() && 'past-events.php' == basename( get_page_template() ) ) :
	
		if ( $sorted_events['past'] ) :
			echo '<div class="events-list past-events-list">';
				echo implode('', $sorted_events['past']);
			echo '</div>';
		endif;
		
	else :
	
		$future_events_title = get_field('acf-options_future_events_list_title', 'option');
		
		if ( $sorted_events['current'] ) :
			echo '<div class="events-list current-events-list">';
				echo implode('', $sorted_events['current']);
			echo '</div>';
		endif;
		
		if ( $sorted_events['future'] ) :
			echo ($future_events_title) ? '<div class="future_events_title"><h3>' . $future_events_title . '</h3></div>' : '';
			
			echo '<div class="events-list future-events-list">';
				echo implode('', $sorted_events['future']);
			echo '</div>';
		endif;
		
	endif;

?>