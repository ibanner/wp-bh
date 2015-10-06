<?php
/**
 * Sites links
 *
 * Display sites links as part of footer
 *
 * @author 		Beit Hatfutsot
 * @package 	bh/views/footer
 * @version     2.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Get global variables
global	$bh_sites,		// BH sites information
		$current_site;	// main / shop

if ($bh_sites) :
	echo '<div class="container">';
		echo '<div class="row sites-links ' . $current_site . '-sites-links">';

			foreach ($bh_sites as $site_name => $site_info) : ?>
				<div class="col-xs-4 site-item">
					<a class="<?php echo $site_name . ($site_name == $current_site ? ' active' : ''); ?>" href="<?php echo $site_info['link']; ?>" target="<?php echo $site_name == 'mjs' ? '_blank' : '_self'; ?>">
						<div class="title"><?php echo $site_info['footer_title']; ?></div>
					</a>
				</div>
			<?php endforeach;

		echo '</div>';
	echo '</div>';
endif;