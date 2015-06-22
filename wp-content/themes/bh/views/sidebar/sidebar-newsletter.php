<?php

	if ( is_active_sidebar('newsletter-side-menu') ) :
		echo '<div class="newsletter-widget">';
			dynamic_sidebar('newsletter-side-menu');
		echo '</div>';
	endif;

?>