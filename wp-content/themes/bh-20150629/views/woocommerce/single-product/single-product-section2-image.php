<?php
/**
 * Single Product experience image
 *
 * @author 		Beit Hatfutsot
 * @package 	bh/views/woocommerce/single-product
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$images	= get_field('acf-options_experience_images', 'option');

echo '<div class="col-sm-7 experience-image">';

	if ($images) :
		$random	= rand( 0, count($images)-1 );
		$image	= $images[$random]['image'];
		
		echo '<img src="' . $image['url'] . '" width="' . $image['width'] . '" height="' . $image['height'] . '" alt="' . $image['alt'] . '" />';
	endif;

echo '</div>';