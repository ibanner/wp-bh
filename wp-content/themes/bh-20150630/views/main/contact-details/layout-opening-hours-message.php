<?php

	$hide				= get_sub_field('hide');
	
	// get opening hours data
	$hours				= get_field('acf-options_opening_hours', 'option');
	
	// get messages
	$open_msg			= get_field('acf-options_open_message', 'option');
	$close_msg			= get_field('acf-options_close_message', 'option');
	$opening_today_msg	= get_field('acf-options_opening_today_message', 'option');
	
	// get some strings related to above messages
	$tommorow_str		= get_field('acf-options_tomorrow_str', 'option');
	$on_day_str			= get_field('acf-options_on_day_str', 'option');
	
	global $sm_row_status, $sm_col_status;
	
	$sm_new_col			= get_sub_field('sm_new_col');
	$sm_cell_height		= get_sub_field('sm_cell_height');
	
	if ($sm_new_col) :
		// sm resolution - close last col
		echo ($sm_col_status == 'open') ? '</div><!-- contact col -->' : '';
		
		// sm resolution - open a new col
		echo '<div class="col-sm-4 col-md-12 col-lg-12">';
		$sm_col_status = 'open';
	endif;
	
	if (!$hide && $hours && $open_msg && $close_msg && $opening_today_msg && $tommorow_str && $on_day_str) :
	
		$status			= 'close';		// [open/close/opening-today]
		$msg			= '';			// message to be displayed
		
		$current_day	= date_i18n('w');	// w => numeric representation of the day of the week - 0 (for Sunday) through 6 (for Saturday)
		$current_time	= date_i18n('Hi');	// H => 24-hour format of an hour with leading zeros; i => Minutes with leading zeros
		
		// locate the closest row in $hours
		$row			= '';
		
		foreach ($hours as $hours_row) :
			if ($hours_row['day'] >= $current_day) :

				if ($hours_row['day'] == $current_day) :
					if ($current_time < $hours_row['open']) :
						// before opening hour today
						$status = 'opening-today';
						$row = $hours_row;
						break;
					elseif ($current_time >= $hours_row['open'] && $current_time <= $hours_row['close']) :
						// open now
						$status = 'open';
						$row = $hours_row;
						break;
					else :
						// after closing hour today
						continue;
					endif;
				else :
					// open on a later day
					$row = $hours_row;
					break;
				endif;

			endif;
		endforeach;
		
		// no match found, first row will be considered
		if (!$row) :
			$row = $hours[0];
		endif;
			
		// build the message
		if ($row) :
			while ( has_sub_field('acf-options_opening_hours', 'option') ) :
				$open_select	= get_sub_field_object('open');
				$close_select	= get_sub_field_object('close');
				
				$day	= $row['day'];
				$open	= $open_select['choices'][$row['open']];
				$close	= $close_select['choices'][$row['close']];
				
				break;
			endwhile;
			
			setlocale(LC_TIME, get_locale() . '.utf8');
			
			if ($status == 'open') :
				$msg = sprintf($open_msg, $close);
			elseif ($status == 'opening-today') :
				$msg = sprintf($opening_today_msg, $open);
			else :
				$msg = sprintf($close_msg, ( ($current_day == $row['day']-1) ? $tommorow_str : $on_day_str . ' ' . strftime( '%A', strtotime('next Sunday + ' . $day . ' days') ) ), $open);
			endif;
			
			// sm resolution - open a new row 
			if ($sm_row_status == 'close') :
				echo '<div class="row">';
				$sm_row_status = 'open';
			endif;
			
			echo '<div class="contact-details-layout contact-details-layout-opening-hours-message"' . ( ($sm_cell_height) ? ' style="height: ' . $sm_cell_height . 'px;"' : '' ) . '>';
				echo ($msg) ? '<div class="msg msg-' . $status . '">' . $msg . '</div>' : '';
			echo '</div>';
		endif;
	
	endif;

?>