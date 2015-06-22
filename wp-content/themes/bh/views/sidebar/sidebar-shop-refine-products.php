<?php

	if ( is_active_sidebar('shop-sidebar-refine-products') ) :
		echo '<div class="shop-sidebar-refine-products">';
			dynamic_sidebar('shop-sidebar-refine-products');
		echo '</div>';
	endif;

?>