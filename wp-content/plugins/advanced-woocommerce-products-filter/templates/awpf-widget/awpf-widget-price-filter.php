<?php
/**
 * awpf-widget-price-filter
 *
 * Display AWPF widget price filter
 *
 * @author		Nir Goldberg
 * @package		templates/awpf-widget
 * @version		1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?>

<div class="awpf-filter awpf-price-filter">

	<?php // filter title ?>
	<?php $price_title = awpf_widget_front()->get_attribute( 'price_title' ); ?>

	<?php if ( $price_title ) { ?>

		<div class="awpf-filter-title awpf-price-filter-title">
			<h3><?php echo $price_title; ?></h3>
		</div>

	<?php } ?>

	<?php // filter content ?>
	<div class="awpf-filter-content">

		<input type="text" id="awpf-price-filter-amount" readonly>
		<div id="awpf-price-filter-slider"></div>

	</div>

</div>