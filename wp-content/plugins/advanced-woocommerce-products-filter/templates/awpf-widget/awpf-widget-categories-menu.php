<?php
/**
 * awpf-widget-categories-menu
 *
 * Display AWPF widget categories menu
 *
 * @author		Nir Goldberg
 * @package		templates/awpf-widget
 * @version		1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?>

<div class="awpf-filter awpf-category-filter">

	<?php // filter title ?>
	<div class="awpf-filter-title awpf-category-filter-title">

		<h3><?php echo apply_filters( 'awpf_widget_categories_menu_title', __('Product Categories', 'awpf') ); ?></h3>
		<?php awpf_get_template_part('loader'); ?>

	</div>

	<?php // filter content ?>
	<div class="awpf-filter-content">

		<ul class="categories">
			<?php apply_filters( 'awpf_widget_categories_menu_items', awpf_widget_front()->display_categories_menu_items() ); ?>
		</ul>

	</div>

</div>