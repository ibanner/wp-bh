<?php
/**
 * Main - Banner layout
 *
 * @author 		Beit Hatfutsot
 * @package 	bh/views/main
 * @version     2.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// check for ACF existence
if ( ! function_exists(get_field) )
	return;

// layout parameters
$slides = get_field('acf-options_main_banner_slides', 'option');

if ( ! $slides )
	return;

$items = array();

foreach ($slides as $s) {
	// get slide parameters
	$type = $s['type'];

	switch ( $type ) {
		
		// event
		case 'event' :

			$event = $s['event'];

			if ( $event ) {
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
						'target'	=> 'self'
					);
		
					$items[] = $item;
				}
			}

			break;

		// custom
		case 'custom' :

			// get custom slide parameters
			$image		= $s['custom_image'];
			$title		= $s['custom_title'];
			$sub_title1	= $s['custom_sub_title1'];
			$sub_title2	= $s['custom_sub_title2'];
			$desc		= $s['custom_desc'];
			$link		= $s['custom_link'];
			$target		= $s['custom_link_target'];

			$item = array(
				'category' 	=> $sub_title1	? $sub_title1	: '',
				'date_html'	=> $sub_title2	? $sub_title2	: '',
				'title' 	=> $title		? $title		: '',
				'text' 		=> $desc		? $desc			: '',
				'image' 	=> $image		? $image		: '',
				'link'		=> $link		? $link			: '',
				'target'	=> $target		? $target		: ''
			);

			$items[] = $item;

			break;

	}
}

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
					echo '<a href="' . $item['link'] . '" ' . ( $item['target'] == 'blank' ? 'target="_blank"' : '' ) . '><img src="' . $item['image']['sizes']['large'] . '" class="slide" /></a>';
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