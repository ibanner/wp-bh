<?php

	$image = '';
	
	if ( is_singular('event') ) :
		// get event main image
		$image = get_field('acf-event_main_image');
	else :
		$image = get_field('acf-featured_image');
	endif;

	if ($image) : ?>
		<div class="featured-image">
			<img src="<?php echo $image['url']; ?>" alt="<?php echo get_the_title(); ?>" />
		</div>
	<?php endif;

?>