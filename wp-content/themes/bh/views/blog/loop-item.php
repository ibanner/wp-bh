<article class="post" id="post-<?php the_ID(); ?>">

	<?php
		
		$read_more_btn = get_field('acf-options_event_btn_read_more', 'option');
		
		// post categories
		echo '<div class="post-categories">';
			the_category(', ', '', $post->ID);
		echo '</div>';
		
		// post title
		echo '<h2 class="post-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h2>';
		
		// post meta data
		get_template_part('views/components/meta', 'single');
		
		// post featured image
		get_template_part('views/components/featured-image');
		
		// post excerpt
		echo '<div class="post-excerpt">';
			the_excerpt();
			echo '<div class="read-more"><a class="btn inline-btn red-btn big" href="' . get_permalink($post->ID) . '">' . $read_more_btn . '</a></div>';
		echo '</div>';
		
	?>

</article>