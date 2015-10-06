<?php
/**
 * Header elements
 *
 * Display icons and links as part of header elements
 *
 * @author 		Beit Hatfutsot
 * @package 	bh/views/header
 * @version     2.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Get global variables
global $current_site;	// main / shop;

$elements = get_field('acf-options_header_icons_links', 'option');

if ($elements) :
	foreach ($elements as $e) :
		// collect element data
		// check whether this element should be displayed according to current site
		$main_header			= $e['main'];
		$shop_header			= $e['shop'];
		
		if ( ! ($main_header && $current_site == 'main' || $shop_header && $current_site == 'shop') )
			continue;

		$type					= $e['type'];
		$link					= $e['link'];
		
		if ($type == 'link') :
			$btn_text			= $e['button_text'];
			$btn_color			= $e['button_color'];
		else :
			$icon_image			= $e['icon_image'];
			$icon_image_hover	= $e['icon_image_hover'];
		endif;

		// echo element html
		echo '<div class="header-element ' . $type . '">';
			if ($type == 'link') :
				echo '<a class="label" href="' . $link . '" style="background-color: ' . $btn_color . ';">' . $btn_text . '</a>';
			else :
				echo '<a class="' . $icon_image . '" href="' . $link . '" onmouseover="this.className=\'' . $icon_image_hover . '\'" onmouseout="this.className=\'' . $icon_image . '\'"></a>';
			endif;
		echo '</div>';
	endforeach;
endif;