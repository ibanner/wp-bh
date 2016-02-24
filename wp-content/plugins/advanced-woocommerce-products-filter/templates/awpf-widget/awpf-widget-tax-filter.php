<?php
/**
 * awpf-widget-tax-filter
 *
 * Display AWPF widget taxonomy filters
 *
 * @author		Nir Goldberg
 * @package		templates/awpf-widget
 * @version		1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// get attributes
$taxonomies = awpf_widget_front()->get_attribute( 'taxonomies' );

if ( ! $taxonomies )
	return;

foreach ($taxonomies as $tax_name => $tax_data) { ?>

	<div class="awpf-filter awpf-tax-filter awpf-tax-filter-<?php echo $tax_name; ?>" <?php echo ( ! $tax_data[1] ? 'style="display: none;"' : '' ); ?>>

		<?php // filter title ?>
		<?php if ( $tax_data[0] ) { ?>
			<div class="awpf-filter-title awpf-tax-filter-title">

				<h3><?php echo $tax_data[0]; ?></h3>

			</div>
		<?php } ?>

		<?php // filter content ?>
		<div class="awpf-filter-content">

			<ul class="tax-terms">
				<?php apply_filters( 'awpf_widget_tax_terms', awpf_widget_front()->display_tax_terms( $tax_name, $tax_data[2] ) ); ?>
			</ul>

		</div>

	</div>

<?php } ?>