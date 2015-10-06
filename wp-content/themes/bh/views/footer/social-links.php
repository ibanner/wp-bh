<?php
/**
 * Soccial links
 *
 * Display social links as part of footer
 *
 * @author 		Beit Hatfutsot
 * @package 	bh/views/footer
 * @version     2.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $current_site;

$links = get_field('acf-options_social_icons_links', 'option');

if ($links) :
	echo '<div class="container">';
		echo '<div class="row social-links ' . $current_site . '-social-links">';
			echo '<ul>';

				foreach ($links as $l) :
					// echo link html
					echo '<li>';
						echo '<a href="' . $l['link'] . '" target="_blank"><i class="fa ' . $l['icon'] . '"></i></a>';
					echo '</li>';
				endforeach;

			echo '</ul>';
		echo '</div>';
	echo '</div>';
endif;