<?php
/*
Widget Name: Shop_Refine_Products
Version: 1.0

Changes Log:
1. Initiation
*/

class Shop_Refine_Products extends WP_Widget
{
	function Shop_Refine_Products() {
		$widget_ops = array('classname' => 'Shop_Refine_Products', 'description' => 'Refine products in shop archive pages, based on price and product custom taxonomies');
		$this->WP_Widget('Shop_Refine_Products', 'Shop Refine Products', $widget_ops);
	}
	
	function form($instance) {
		$instance = wp_parse_args((array) $instance,
			array (
				'title'			=> '',
				'price_title'	=> '',
				'taxonomies'	=> ''
			));
			
		$title					= $instance['title'];
		$price_title			= $instance['price_title'];
		
		$taxonomies				= isset($instance['taxonomies']) ? $instance['taxonomies'] : array();
		$taxonomies_html		= array();
		$taxonomies_counter		= 0;
		
		$taxonomies_objects		= get_object_taxonomies('product', 'objects');
		
		if ($taxonomies && $taxonomies_objects) :
			foreach ($taxonomies as $tax) :
				if ( isset($tax['name']) ) :
				
					// generate select options
					$options = '';
					foreach ($taxonomies_objects as $tax_obj) :
						$selected = ( $tax_obj->name == $tax['name'] ) ? ' selected="selected"' : '';
						
						$options .= '<option value="' . $tax_obj->name . '"' . $selected . '>';
						$options .= $tax_obj->label;
						$options .= '</option>';
					endforeach;
					
					// store taxonomy html
					$taxonomies_html[] = sprintf(
						'<div class="tax">' .
							'<div class="inline-tax inline-left"><label for="%1$s[%2$s][title]">Title: <input class="widefat" id="%1$s[%2$s][title]" name="%1$s[%2$s][title]" type="text" value="%3$s" /></label></div>' .
							'<div class="inline-tax">' .
								'<label for="%1$s[%2$s][name]">Taxonomy: ' .
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
					
				endif;
			endforeach;
		endif;
		
		?>
			<style>
				.widget-content .inline {
					float: left;
					width: 49%;
				}
				
				.widget-content .inline-tax {
					float: left;
					width: 42%;
				}
				
				.widget-content .inline-left {
					margin-right: 2%;
				}
				
				.widget-content .taxonomies {
					margin-top: 10px;
				}
				
				.widget-content .tax {
					margin-bottom: 10px;
					overflow: hidden;
				}
				
				.widget-content .add-tax,
				.widget-content .remove-tax {
					color: #0000ff;
					cursor: pointer;
					display: inline-block;
				}
				
				.widget-content .remove-tax {
					margin: 23px 0 0 5px;
				}
			</style>
			
			<script>
				var taxonomiesField	= <?php echo json_encode($this->get_field_name('taxonomies')); ?>;
				var taxonomiesNum	= <?php echo json_encode($taxonomies_counter) ?>;
				
				var $ = jQuery.noConflict();
				$(document).ready(function() {
					var count	= taxonomiesNum,
						options	=
							'<?php
								if ($taxonomies_objects) :
									foreach ($taxonomies_objects as $tax_obj) :
										echo '<option value="' . $tax_obj->name . '">';
										echo $tax_obj->label;
										echo '</option>';
									endforeach;
								endif;
							?>';
					
					// add taxonomy
					$('.<?php echo $this->get_field_id('add-tax'); ?>').click(function() {
						var tax =
							'<div class="tax">' +
								'<div class="inline-tax inline-left"><label for="' + taxonomiesField + '[' + count + '][title]">Title: <input class="widefat" id="' + taxonomiesField + '[' + count + '][title]" name="' + taxonomiesField + '[' + count + '][title]" type="text" value="" /></label></div>' +
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
					
					// remove taxonomy
					$(".remove-tax").live('click', function() {
						$(this).parent().remove();
					});
				});
			</script>
			
			<p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>
			<p><label for="<?php echo $this->get_field_id('price_title'); ?>">Price Filter Title: <input class="widefat" id="<?php echo $this->get_field_id('price_title'); ?>" name="<?php echo $this->get_field_name('price_title'); ?>" type="text" value="<?php echo esc_attr($price_title); ?>" /></label></p>
			<div>
				<label>Product Custom Taxonomies:</label>
				
				<div class="taxonomies" id="<?php echo $this->get_field_id('taxonomies'); ?>">
					<?php echo implode('', $taxonomies_html); ?>
				</div>
				
				<span class="add-tax <?php echo $this->get_field_id('add-tax'); ?>">+ Add Taxonomy</span>
			</div>
		<?php
	}
	
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		
		$instance['title']			= $new_instance['title'];
		$instance['price_title']	= $new_instance['price_title'];
		
		$instance['taxonomies']		= array();
		
		if ( isset($new_instance['taxonomies']) )
			foreach ($new_instance['taxonomies'] as $tax)
				if (trim($tax['name']) !== '')
					$instance['taxonomies'][] = $tax;
		
		return $instance;
	}
	
	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
		
		if ( ! is_post_type_archive('product') && ! is_tax( get_object_taxonomies('product') ) )
			return;
			
		// declare filter values
		$taxonomy			= get_query_var('taxonomy');		
		$term_id			= get_queried_object_id();
		$min_price			= null;
		$max_price			= null;
		$min_handle_price	= null;
		$max_handle_price	= null;
		$taxonomies			= array();
		
		/**
		 * initiate $taxonomies as an array of arrays (taxonomies and terms data)
		 * 
		 * $taxonomies[ {taxonomy name} ][0]					=> taxonomy filter title
		 * $taxonomies[ {taxonomy name} ][1]					=> number of products associated with this taxonomy
		 * $taxonomies[ {taxonomy name} ][2][ {term ID} ][0]	=> term name
		 * $taxonomies[ {taxonomy name} ][2][ {term ID} ][1]	=> number of products associated with this term
		 * $taxonomies[ {taxonomy name} ][2][ {term ID} ][2]	=> whether this term is checked in taxonomy filter [1 = true / 0 = false]
		 */
		if ( $instance['taxonomies'] ) :
			foreach ($instance['taxonomies'] as $tax) :
				// get taxonomy terms
				$args = array(
					'orderby'	=> 'term_order'
				);
				$terms = get_terms($tax['name'], $args);
				
				if ( count($terms) > 0 ) :
				
					$taxonomies[ $tax['name'] ] = array();
					$taxonomies[ $tax['name'] ][0] = $tax['title'];		// holds taxonomy filter title
					$taxonomies[ $tax['name'] ][1] = 0;					// holds number of products associated with this taxonomy
					$taxonomies[ $tax['name'] ][2] = array();			// holds array of term IDs
					
					foreach ($terms as $term) :
						$taxonomies[ $tax['name'] ][2][$term->term_id] = array();
						$taxonomies[ $tax['name'] ][2][$term->term_id][0] = $term->name;	// holds term name
						$taxonomies[ $tax['name'] ][2][$term->term_id][1] = 0;				// holds number of products associated with this term
						$taxonomies[ $tax['name'] ][2][$term->term_id][2] = 0;				// indicates whether this term is checked in taxonomy filter
					endforeach;
					
				endif;
			endforeach;
		endif;
		
		// initiate filter values
		BH_init_product_filter_values( $taxonomy, $term_id, $min_price, $max_price, $min_handle_price, $max_handle_price, $taxonomies );
		
		?>
		
			<script>
				_BH_refine_products_taxonomy			= '<?php echo $taxonomy; ?>';
				_BH_refine_products_term_id				=  <?php echo $term_id; ?>;
				_BH_refine_products_min_price			=  <?php echo $min_price; ?>;
				_BH_refine_products_max_price			=  <?php echo $max_price; ?>;
				_BH_refine_products_min_handle_price	=  <?php echo $min_handle_price; ?>;
				_BH_refine_products_max_handle_price	=  <?php echo $max_handle_price; ?>;
				_BH_refine_products_taxonomies			=  <?php echo json_encode($taxonomies); ?>;
				_BH_refine_products_api					= '<?php echo TEMPLATE; ?>/functions/widgets/shop-refine-products/refine-products-api.php';
				_BH_refine_currency						= '<?php echo (ICL_LANGUAGE_CODE == 'he') ? '₪': '$'?>';
			</script>
			
			<script src="<?php echo TEMPLATE; ?>/functions/widgets/shop-refine-products/refine-products.min.js"></script>
			
		<?php
		
		// widget content
		echo $before_widget;
		
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance);
		
		if (!empty($title)) :
			echo $before_title;
				echo $title;
				echo '<div class="loader">';
					get_template_part('views/components/loader');
				echo '</div>';
			echo $after_title;
		endif;
			
		echo '<div class="widgetcontent">';
		
			// price filter
			echo '<div class="price-filter">';
				echo ( $instance['price_title'] ) ? '<div class="price-title">' . $instance['price_title'] . '</div>' : '';
				
				echo '<input type="text" id="price-filter-amount" readonly>';
				echo '<div id="price-filter-slider"></div>';
			echo '</div>';
			
			// taxonomy filters
			if ($taxonomies) :
				foreach ($taxonomies as $tax_name => $tax_data) :
				
					// display taxonomy filter if there are terms
					if ( $tax_data[1] > 0 ) {
						echo '<div class="tax-filter tax-filter-' . $tax_name . '">';
							echo ( $tax_data[0] ) ? '<div class="tax-title">' . $tax_data[0] . '</div>' : '';
							
							echo '<div class="tax-terms">';
								foreach ($tax_data[2] as $term_id => $term_data) :
									if ($term_data[1] > 0) :
										echo '<label>';
											echo '<input type="checkbox" name="' . $this->get_field_name($tax_name) . '[]" id="' . $term_id . '" value="' . $term_id . '" />';
											echo '<span>' . $term_data[0] . ' (' . $term_data[1] . ')</span>';
										echo '</label>';
									endif;
								endforeach;
							echo '</div>';
						echo '</div>';
					}
					
				endforeach;
			endif;
			
		echo '</div>';
		
		echo $after_widget;
	}
}
add_action( 'widgets_init', create_function('', 'return register_widget("Shop_Refine_Products");') );