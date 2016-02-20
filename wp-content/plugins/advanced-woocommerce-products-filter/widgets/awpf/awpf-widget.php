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

		// form fields
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
	 * @param		$instance (array) widget instance values
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
	 * @param		$new_instance (array) widget instance new values
	 * @param		$old_instance (array) widget instance old values
	 * @return		$instance (array) widget instance updated values
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
	 * @param		$args (array)
	 * @param		$instance (array)
	 * @return		N/A
	 */
	function widget($args, $instance) {

		extract($args, EXTR_SKIP);

		// exit if declared out of product archive or product taxonomy
		if ( ! is_post_type_archive('product') && ! is_tax( get_object_taxonomies('product') ) )
			return;

		// Widget content
		echo $before_widget;

		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance);
		if ( ! empty($title) ) {
			echo
				$before_title .
					$title .
					'<div class="loader">' .
						'<img src="' . awpf_get_dir('assets/images/ajax-loader.gif') . '" width="16" height="16" />' .
					'</div>' .
				$after_title;
		}

		awpf_widget_front()->initialize( $instance['show_categories_menu'], $instance['show_price_filter'], $instance['price_title'], $instance['taxonomies'] );

		echo $after_widget;
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
	 * get_form_field
	 *
	 * This function will return a value from the form_fields array found in AWPF_Widget object
	 *
	 * @since		1.0
	 * @param		$name (string) the form field name to return
	 * @return		(mixed)
	 */
	private function get_form_field( $name, $default = null ) {

		$form_field = awpf_maybe_get( $this->form_fields, $name, $default );

		// return
		return $form_field;

	}

	/**
	 * generate_widget_form
	 *
	 * This function will generate the widget form
	 *
	 * @since		1.0
	 * @param		N/A
	 * @return		N/A
	 */
	private function generate_widget_form() {

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
	private function generate_taxonomies_html( $taxonomies, $taxonomies_objects ) {

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