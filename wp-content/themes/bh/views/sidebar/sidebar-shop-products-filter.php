<?php

	if ( is_active_sidebar('shop-sidebar-products-filter') ) :
		echo '<div class="shop-sidebar-products-filter">';
			dynamic_sidebar('shop-sidebar-products-filter');
		echo '</div>';
	endif;

?>