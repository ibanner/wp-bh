<?php
/**
 * Footer menu
 *
 * Display menu as part of footer
 *
 * @author 		Beit Hatfutsot
 * @package 	bh/views/footer
 * @version     2.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Get global variables
global $menu;	// Complete menu HTML structure

if ($menu) :
	echo '<div class="container">';
		echo '<nav class="row footer-menu">';
			echo '<ul>';
				echo $menu;
			echo '</ul>';
		echo '</nav>';
	echo '</div>';
endif;