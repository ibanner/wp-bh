<?php

	if ( is_active_sidebar('shop-sidebar-recently-viewed') ) :
		echo '<div class="shop-sidebar-recently-viewed">';
			dynamic_sidebar('shop-sidebar-recently-viewed');
		echo '</div>';
	endif;

?>