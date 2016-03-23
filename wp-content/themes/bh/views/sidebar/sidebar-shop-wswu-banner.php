<?php
/**
 * The Template for displaying Why Shop With Us banner
 *
 * @author 		Beit Hatfutsot
 * @package 	bh/views/sidebar
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// get fields
$reasons	= get_field('acf-options_experience_reasons',	'option');
$wswu_page	= get_field('acf-options_experience_page',		'option');

if ($reasons) {

	$reasons = array_values($reasons); ?>

	<div class="wswu-sidebar-banner hidden-xs">

		<div class="wswu-content">

			<span class="prefix"><?php _e('<strong>Why shop with us?</strong> There are lots of reasons. For instance, ', 'BH'); ?></span>
			<span class="reason"><?php echo $reasons[ rand(0, count($reasons)-1) ]['reason']; ?></span>
			<?php if ($wswu_page) { ?>
				<div class="suffix"><a href="<?php echo get_permalink($wswu_page->ID); ?>"><?php _e('And there are more', 'BH'); ?></a></div>
			<?php } ?>

		</div>

		<div class="wswu-logo"></div>

	</div>

<?php }