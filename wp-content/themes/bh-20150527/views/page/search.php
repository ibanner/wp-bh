<?php

	global $section_title;

	$search_query = get_search_query();
	$section_title = ($search_query) ? sprintf(__('Search Results for: &#8216;%s&#8217;', 'BH'), $search_query ) : __('Search Results', 'BH');

	// page title
	get_template_part('views/components/page', 'title');

?>
	
<div class="content">

	<?php
		if (have_posts()) :

			while (have_posts()) : the_post();
				get_template_part('views/page/loop');
			endwhile;

		else :

			get_template_part('views/components/not-found');

		endif;
	?>

</div>