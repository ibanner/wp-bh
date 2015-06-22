<?php

	if ( function_exists('yoast_breadcrumb') ) :
		echo '<small>';
			yoast_breadcrumb('<p id="breadcrumbs">','</p>');
		echo '</small>';
	endif;

?>