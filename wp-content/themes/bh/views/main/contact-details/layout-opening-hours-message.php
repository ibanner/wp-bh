<?php
/**
 * layout-opening-hours-message.php
 *
 * @author		Beit Hatfutsot
 * @package		bh/views/main/contact-details
 * @version		2.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$hide				= get_sub_field('hide');

global $sm_row_status, $sm_col_status;

$sm_new_col			= get_sub_field('sm_new_col');
$sm_cell_height		= get_sub_field('sm_cell_height');

// sm resolution - open a new row
if ($sm_row_status == 'close') {
	echo '<div class="row">';
	$sm_row_status = 'open';
}

if ($sm_new_col) {
	// sm resolution - close last col
	echo ($sm_col_status == 'open') ? '</div><!-- contact col -->' : '';
	
	// sm resolution - open a new col
	echo '<div class="col-sm-4 col-md-12 col-lg-12">';
	$sm_col_status = 'open';
}

if ( ! $hide ) { ?>
	<div class="contact-details-layout contact-details-layout-opening-hours-message"' . ( ($sm_cell_height) ? ' style="height: ' . $sm_cell_height . 'px;"' : '' ) . '></div>
<?php }