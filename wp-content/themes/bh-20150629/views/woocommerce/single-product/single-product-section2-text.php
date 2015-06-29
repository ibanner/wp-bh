<?php
/**
 * Single Product experience text
 *
 * @author 		Beit Hatfutsot
 * @package 	bh/views/woocommerce/single-product
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$title	= get_field('acf-options_experience_title_' . ICL_LANGUAGE_CODE, 'option');
$text	= get_field('acf-options_experience_text_' . ICL_LANGUAGE_CODE, 'option');

echo '<div class="col-sm-5 experience-text">';

	echo ($title) ? '<h2 class="title">' . $title . '</h2>' : '';
	echo ($text) ? '<div class="text">' . $text . '</div>' : '';

echo '</div>';