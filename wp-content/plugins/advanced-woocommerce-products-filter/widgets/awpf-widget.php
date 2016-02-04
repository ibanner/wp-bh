<?php
/**
 * Widget Name: AWPF Widget
 *
 * @author		Nir Goldberg
 * @package		widgets
 * @version		1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action('widgets_init', function() {

	register_widget( 'AWPF_Widget' );

});

class AWPF_Widget extends WP_Widget {

	var $form_fields;

	/**
	 * __construct
	 *
	 * Widget constructor
	 *
	 * @since		1.0
	 * @param		N/A
	 * @return		N/A
	 */
	function __construct() {

		parent::__construct(

			'AWPF_Widget',
			__('Advanced WooCommerce Products Filter', 'awpf'),
			array(
				'description' => __('Add WooCommerce products filter, based on price and product custom taxonomies', 'awpf' )
			)

		);

		// filter values
		$this->form_fields = array(
			'title'					=> '',
			'show_categories_menu'	=> '',
			'show_price_filter'		=> '',
			'price_title'			=> '',
			'taxonomies'			=> ''
		);

		// actions
		add_action( 'sidebar_admin_setup', array($this, 'admin_enqueue_scripts') );

	}

	/**
	 * form
	 *
	 * Admin widget form
	 *
	 * @since		1.0
	 * @param		N/A
	 * @return		N/A
	 */
	function form($instance) {

		$this->form_fields['title']					= isset( $instance['title'] )					? $instance['title']				: '';
		$this->form_fields['show_categories_menu']	= isset( $instance['show_categories_menu'] )	? $instance['show_categories_menu']	: '';
		$this->form_fields['show_price_filter']		= isset( $instance['show_price_filter'] )		? $instance['show_price_filter']	: '';
		$this->form_fields['price_title']			= isset( $instance['price_title'] )				? $instance['price_title']			: '';
		$this->form_fields['taxonomies']			= isset( $instance['taxonomies'] )				? $instance['taxonomies']			: array();

		$this->generate_widget_form();

	}
	
	/**
	 * update
	 *
	 * Widget update
	 *
	 * @since		1.0
	 * @param		N/A
	 * @return		N/A
	 */
	function update($new_instance, $old_instance) {

		$instance = $old_instance;
		
		$instance['title']					= $new_instance['title'];
		$instance['show_categories_menu']	= $new_instance['show_categories_menu'];
		$instance['show_price_filter']		= $new_instance['show_price_filter'];
		$instance['price_title']			= $new_instance['price_title'];
		
		$instance['taxonomies']				= array();
		
		if ( isset($new_instance['taxonomies']) )
			foreach ($new_instance['taxonomies'] as $tax)
				if (trim($tax['name']) !== '')
					$instance['taxonomies'][] = $tax;
		
		// return
		return $instance;

	}
	
	/**
	 * widget
	 *
	 * Widget frontend
	 *
	 * @since		1.0
	 * @param		N/A
	 * @return		N/A
	 */
	function widget($args, $instance) {

		extract($args, EXTR_SKIP);

		// exit if declared out of product archive or product taxonomy
		if ( ! is_post_type_archive('product') && ! is_tax( get_object_taxonomies('product') ) )
			return;
			
		// filter values
		$taxonomy			= get_query_var('taxonomy');		
		$term_id			= get_queried_object_id();
		$min_price			= null;
		$max_price			= null;
		$min_handle_price	= null;
		$max_handle_price	= null;
		$categories			= array();
		$taxonomies			= array();
		$products			= array();

		// initiate $categories
		if ( $instance['show_categories_menu'] ) {
			awpf_init_categories( $categories );
		}

		// initiate $taxonomies
		if ( $instance['taxonomies'] ) {
			awpf_init_taxonomies( $instance['taxonomies'], $taxonomies );
		}

		if ( ! $instance['show_price_filter'] && ( ! $instance['show_categories_menu'] || ! $categories ) && ! $taxonomies )
			return;
			
		/**
		 * 1. Initiate filter values
		 * 2. Initiate $products as an array of arrays (products and terms associated with each product)
		 */
		awpf_init_products_filter_values( $taxonomy, $term_id, $min_price, $max_price, $min_handle_price, $max_handle_price, $taxonomies, $products );

		if ( ! $products )
			return;

		?>
		
		<script>
			_AWPF_products_filter_taxonomy				= '<?php echo $taxonomy; ?>';
			_AWPF_products_filter_term_id				=  <?php echo $term_id; ?>;
			_AWPF_products_filter_min_price				=  <?php echo $min_price; ?>;
			_AWPF_products_filter_max_price				=  <?php echo $max_price; ?>;
			_AWPF_products_filter_min_handle_price		=  <?php echo $min_handle_price; ?>;
			_AWPF_products_filter_max_handle_price		=  <?php echo $max_handle_price; ?>;
			_AWPF_products_filter_categories			=  <?php echo json_encode($categories); ?>;
			_AWPF_products_filter_taxonomies			=  <?php echo json_encode($taxonomies); ?>;
			_AWPF_products_filter_products				=  <?php echo json_encode($products); ?>;
			_AWPF_products_filter_currency				= '<?php echo html_entity_decode( get_woocommerce_currency_symbol() ); ?>';
			_AWPF_products_filter_show_price_filter		=  <?php echo ( $instance['show_price_filter'] ) ? 1 : 0; ?>;
			_AWPF_products_filter_show_categories_menu	=  <?php echo ( $instance['show_categories_menu'] ) ? 1 : 0; ?>;
		</script>

		<?php
			wp_enqueue_style( 'jquery-ui' );
			wp_enqueue_script( 'jquery-ui' );
			wp_enqueue_script( 'awpf-products-filter' );
		?>

		<?php
			// Widget content
			echo $before_widget;
		?>

		<?php
			$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance);

			/**
			 * awpf_before_main_content hook
			 *
			 * @hooked awpf_output_widget_title - 10
			 */
			do_action( 'awpf_before_main_content', $title );
		?>

		<?php

		if ( ! empty($title) )
			echo
				$before_title .
					$title .
					'<div class="loader">' .
						'<img src="' . awpf_get_dir('images/ajax-loader.gif') . '" width="16" height="16" />' .
					'</div>' .
				$after_title;

		echo '<div class="widgetcontent">';
		
			// Categories menu
			if ( $instance['show_categories_menu'] && $categories ) {

				echo '<div class="awpf-category-filter">';
					echo '<div class="category-filter-title">';
						echo apply_filters( 'awpf_product_categories_title', __('Product Categories', 'awpf') );
					echo '</div>';

					echo '<ul class="categories">';
						foreach ( $categories[0] as $category ) {
							if ( $category[4] ) {
								awpf_product_categories_menu_item($categories, $category, 0);
							} else {
								echo '<li>';
									echo '<span class="item-before"></span>';
									echo '<a href="' . $category[1] . '"><span>' . get_cat_name( $category[0] ) . '</span> <span class="count">(' . $category[2] . ')</span></a>';
								echo '</li>';
							}
						}
					echo '</ul>';
				echo '</div>';

			}

			// Price filter
			if ( $instance['show_price_filter'] ) {

				echo '<div class="awpf-price-filter">';
					echo ( $instance['price_title'] ) ? '<div class="awpf-price-filter-title">' . $instance['price_title'] . '</div>' : '';
					
					echo '<input type="text" id="awpf-price-filter-amount" readonly>';
					echo '<div id="awpf-price-filter-slider"></div>';
				echo '</div>';

			}
			
			// Taxonomy filters
			if ($taxonomies) {

				foreach ($taxonomies as $tax_name => $tax_data) {
					// Display taxonomy filter if there are terms
					if ( $tax_data[1] > 0 ) {
						echo '<div class="awpf-tax-filter awpf-tax-filter-' . $tax_name . '">';
							echo ( $tax_data[0] ) ? '<div class="tax-filter-title">' . $tax_data[0] . '</div>' : '';
							
							echo '<div class="tax-terms">';
								foreach ($tax_data[2] as $term_id => $term_data) {
									if ($term_data[0] > 0) {
										$term_name = get_term_by('id', $term_id, $tax_name)->name;

										echo '<label>';
											echo '<input type="checkbox" name="' . $this->get_field_name($tax_name) . '[]" id="' . $term_id . '" value="' . $term_id . '" />' . $term_name . ' <span class="count">(' . $term_data[0] . ')</span>';
										echo '</label>';
									}
								}
							echo '</div>';
						echo '</div>';
					}
				}

			}
			
		echo '</div>';
		
		echo $after_widget;
	}

	/**
	 * get_form_field
	 *
	 * This function will return a value from the form_fields array found in AWPF_Widget object
	 *
	 * @since		1.0
	 * @param		$name (string) the form field name to return
	 * @return		(mixed)
	 */
	function get_form_field( $name, $default = null ) {

		$form_field = awpf_maybe_get( $this->form_fields, $name, $default );

		// return
		return $form_field;

	}

	/**
	 * admin_enqueue_scripts
	 *
	 * This function will add the already registered scripts and styles
	 *
	 * @since		1.0
	 * @param		N/A
	 * @return		N/A
	 */
	function admin_enqueue_scripts() {

		wp_enqueue_style('awpf-admin-style');

	}

	/**
	 * generate_widget_form
	 *
	 * This function will generate the widget form
	 *
	 * @since		1.0
	 * @param		$taxonomies (array) array of taxonomies input fields within widget form
	 * @return		N/A
	 */
	function generate_widget_form() {

		$title					= $this->get_form_field( 'title' );
		$show_categories_menu	= $this->get_form_field( 'show_categories_menu' );
		$show_price_filter		= $this->get_form_field( 'show_price_filter' );
		$price_title			= $this->get_form_field( 'price_title' );
		$taxonomies				= $this->get_form_field( 'taxonomies' );

		$taxonomies_objects		= get_object_taxonomies( 'product', 'objects' );
		$taxonomies_html		= $this->generate_taxonomies_html( $taxonomies, $taxonomies_objects );

		?>

		<script>
			var taxonomiesField	= <?php echo json_encode( $this->get_field_name('taxonomies') ); ?>;
			var taxonomiesNum	= <?php echo json_encode( count($taxonomies_html) ); ?>;
			
			var $ = jQuery.noConflict();
			$(document).ready(function() {
				var count	= taxonomiesNum,
					options	=
						'<?php
							if ($taxonomies_objects) {
								foreach ($taxonomies_objects as $tax_obj) {
									echo '<option value="' . $tax_obj->name . '">';
									echo $tax_obj->label;
									echo '</option>';
								}
							}
						?>';
				
				// Add taxonomy
				$('.<?php echo $this->get_field_id('add-tax'); ?>').click(function() {
					var tax =
						'<div class="tax">' +
							'<div class="inline-tax inline-left">Title: <label for="' + taxonomiesField + '[' + count + '][title]"><input class="widefat" id="' + taxonomiesField + '[' + count + '][title]" name="' + taxonomiesField + '[' + count + '][title]" type="text" value="" /></label></div>' +
							'<div class="inline-tax">Taxonomy: ' +
								'<label for="' + taxonomiesField + '[' + count + '][name]">' +
									'<select class="widefat" id="' + taxonomiesField + '[' + count + '][name]" name="' + taxonomiesField + '[' + count + '][name]">' +
										options +
									'</select>' +
								'</label>' +
							'</div>' +
							'<span class="remove-tax">Remove</span>' +
						'</div>';
					
					$('#<?php echo $this->get_field_id('taxonomies'); ?>').append(tax);
					
					count++;
				});
				
				// Remove taxonomy
				$(".remove-tax").live('click', function() {
					$(this).parent().remove();
				});
			});
		</script>
			
		<p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr( $this->form_fields['title'] ); ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id('show_categories_menu'); ?>"><input id="<?php echo $this->get_field_id('show_categories_menu'); ?>" name="<?php echo $this->get_field_name('show_categories_menu'); ?>" type="checkbox" <?php echo esc_attr($show_categories_menu) ? 'checked' : ''; ?> />Show Product Categories Menu</label></p>
		<p><label for="<?php echo $this->get_field_id('show_price_filter'); ?>"><input id="<?php echo $this->get_field_id('show_price_filter'); ?>" name="<?php echo $this->get_field_name('show_price_filter'); ?>" type="checkbox" <?php echo esc_attr($show_price_filter) ? 'checked' : ''; ?> />Show Price Filter</label></p>
		<p><label for="<?php echo $this->get_field_id('price_title'); ?>">Price Filter Title: <input class="widefat" id="<?php echo $this->get_field_id('price_title'); ?>" name="<?php echo $this->get_field_name('price_title'); ?>" type="text" value="<?php echo esc_attr($price_title); ?>" /></label></p>
		<div>
			<label>Product Taxonomies:</label>
			
			<div class="awpf-taxonomies" id="<?php echo $this->get_field_id('taxonomies'); ?>">
				<?php echo implode('', $taxonomies_html); ?>
			</div>
			
			<span class="awpf-add-tax <?php echo $this->get_field_id('add-tax'); ?>">+ Add Taxonomy</span>
		</div>

		<?php

	}

	/**
	 * generate_taxonomies_html
	 *
	 * This function will generate taxonomies HTML fields for the widget form 
	 *
	 * @since		1.0
	 * @param		$taxonomies (array) array of taxonomies input fields within widget form
	 * @param		$taxonomies_objects (array) array of product taxonomies
	 * @return		(array)
	 */
	function generate_taxonomies_html( $taxonomies, $taxonomies_objects ) {

		$taxonomies_html	= array();
		$taxonomies_counter	= 0;
		
		if ($taxonomies && $taxonomies_objects) {

			foreach ($taxonomies as $tax) {

				if ( isset($tax['name']) ) {
				
					// Generate select options
					$options = '';
					foreach ($taxonomies_objects as $tax_obj) {
						$selected = ( $tax_obj->name == $tax['name'] ) ? ' selected="selected"' : '';
						
						$options .= '<option value="' . $tax_obj->name . '"' . $selected . '>';
						$options .= $tax_obj->label;
						$options .= '</option>';
					}
					
					// Store taxonomy HTML
					$taxonomies_html[] = sprintf(
						'<div class="tax">' .
							'<div class="inline-tax inline-left">Title: <label for="%1$s[%2$s][title]"><input class="widefat" id="%1$s[%2$s][title]" name="%1$s[%2$s][title]" type="text" value="%3$s" /></label></div>' .
							'<div class="inline-tax">Taxonomy: ' .
								'<label for="%1$s[%2$s][name]">' .
									'<select class="widefat" id="%1$s[%2$s][name]" name="%1$s[%2$s][name]">' .
										$options .
									'</select>' .
								'</label>' .
							'</div>' .
							'<span class="remove-tax">Remove</span>' .
						'</div>',
						
						$this->get_field_name('taxonomies'),
						$taxonomies_counter,
						esc_attr($tax['title'])
					);
					
					$taxonomies_counter++;
					
				}

			}

		}

		// return
		return $taxonomies_html;

	}

}