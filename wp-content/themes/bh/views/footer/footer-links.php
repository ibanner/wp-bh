<?php
/**
 * Footer links
 *
 * Display link boxes as part of footer
 *
 * @author 		Beit Hatfutsot
 * @package 	bh/views/footer
 * @version     2.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Get global variables
global $current_site;	// main / shop;

$links = get_field('acf-options_' . $current_site . '_site_footer_links', 'option');

if ($links) :
	echo '<div class="container">';
		echo '<div class="row footer-links">';

			foreach ($links as $l) :
				// collect link data
				$title		= $l['title'];
				$big_icon	= $l['big_icon'];
				$small_icon	= $l['small_icon'];
				$link		= $l['link'];

				// echo link html
				echo '<div class="col-xs-4 link-box-wrapper">';
					echo '<div class="link-box">';
						echo '<a href="' . $link . '">';

							echo '<div class="before-link"></div>';

							echo '<div class="link">';

								// title
								echo '<div class="small-icon visible-xs">' . ( $small_icon ? '<span class="' . $small_icon . '"></span>' : '' ) . '</div>';
								echo '<div class="title">' . ( $big_icon ? '<span class="' . $big_icon . ' hidden-xs"></span>' : '' ) . $title . '</div>';
								
								// learn more
								echo '<div class="more">' . __('Learn more', 'BH') . '</div>';

							echo '</div>';
							
						echo '</a>';
					echo '</div>';
				echo '</div>';
			endforeach;

		echo '</div>';
	echo '</div>';
endif;