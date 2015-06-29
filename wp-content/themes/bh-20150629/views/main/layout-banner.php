<?php

	global $sitepress;
	
	// get locale string
	$locale = $sitepress->get_locale(ICL_LANGUAGE_CODE);
	
	$args = array(
		'post_type'			=> 'event',
		'posts_per_page'	=> 5,
		'orderby'			=> 'menu_order',
		'order'				=> 'ASC',
		'meta_query'		=> array(
			'relation'		=> 'AND',
			array(
				'key'		=> 'acf-event_homepage_banner_indicator',
				'value'		=> true
			),
			array(
				'key'		=> 'acf-event_end_date',
				'value'		=> date_i18n('Ymd'),
				'type'		=> 'DATE',
				'compare'	=> '>='
			)
		)
	);
	
	if ( function_exists('BH_get_cached_wp_query') ) :
		$events = BH_get_cached_wp_query($args, 'events-' . ICL_LANGUAGE_CODE);
	else :
		$events = array();
		$query = new WP_Query($args);
		
		if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();
			$events[] = $post;
		endwhile; endif; wp_reset_postdata();
	endif;
	
	if ($events) :
		$items = array();
		
		foreach ($events as $event) :
			$image = get_field('acf-event_main_image', $event->ID);
			
			if ($image) {
	
				$event_cats	= wp_get_post_terms($event->ID, 'event_category');
				$cat		= $event_cats ? get_field('acf-event_category_singular_name', 'event_category_' . $event_cats[0]->term_id) : ''; 
	
				$item = array(
					'category' 	=> $cat,
					'date_html'	=> BH_get_event_date($event->ID),
					'title' 	=> get_the_title($event->ID),
					'text' 		=> get_field('acf-event_description', $event->ID),
					'image' 	=> $image,
					'link'		=> get_permalink($event->ID),
				);
	
				$items[] = $item;
			}
		endforeach;
	endif; 

?>

<section class="main-layout main-layout-banner">
	<div class="container">
		<div id="slideshow-wrapper">
			<div id="description-wrapper">
				<div id="up-arrow"></div>
				<div id="description">
					<div id="description-cell">
						<div id="category"></div>		
						<a class="desc-link" href="">
							<div id="title"></div>
						</a>
						<div id="date"></div>
						<div id="text"></div>
						<?php /*<a class="desc-link read-more" href="">read more</a>*/ ?>
					</div>
				</div>
				<div id="down-arrow"></div>
			</div>
			<div id="slides" class="slides-reset">
			<?php
				echo '<img src="' . $items[count($items)-1]['image']['sizes']['large'] . '" class="slide" />';
				foreach($items as $index => $item) {
					echo '<a href="' . $item['link'] . '"><img src="' . $item['image']['sizes']['large'] . '" class="slide" /></a>';
				}
				echo '<img src="' . $items[0]['image']['sizes']['large'] . '" class="slide" />';
			?>
			</div>
			<div id="mini-gallery">
			<?php
				foreach($items as $index => $item) {
					echo '<div class="mini-gallery-item-wrapper" index="' . $index . '">';
					echo '<div class="mini-gallery-item" style="background-image: url(' . $item['image']['sizes']['large'] . ')"></div>';
					echo '<div class="mini-gallery-title">' . $item['title'] . '</div>';
					echo '</div>';
				}
			?>
			</div>
		</div>
	</div>
</section>

<script>
	var items = <?php echo json_encode($items); ?>;
</script>

<?php wp_enqueue_script('banner'); ?>