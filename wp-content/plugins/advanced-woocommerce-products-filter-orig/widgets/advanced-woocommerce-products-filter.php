<?php
/**
 * Widget Name: Advanced_WooCommerce_Products_Filter
 *
 * @author 		Nir Goldberg
 * @package 	advanced-woocommerce-products-filter/widgets
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists('acf') ) :



class Advanced_WooCommerce_Products_Filter extends WP_Widget
{
	function __construct(argument) {
			# code...
		}
	}

	function Advanced_WooCommerce_Products_Filter() {
		$widget_ops = array('classname' => 'Advanced_WooCommerce_Products_Filter', 'description' => 'Add WooCommerce products filter, based on price and product custom taxonomies');
		$this->WP_Widget('Advanced_WooCommerce_Products_Filter', 'Advanced WooCommerce Products Filter', $widget_ops);
	}
	
	function form($instance) {
		$instance = wp_parse_args((array) $instance,
			array (
				'title'					=> '',
				'show_categories_menu'	=> '',
				'show_price_filter'		=> '',
				'price_title'			=> '',
				'taxonomies'			=> ''
			));
			
		$title							= $instance['title'];
		$show_categories_menu			= $instance['show_categories_menu'];
		$show_price_filter				= $instance['show_price_filter'];
		$price_title					= $instance['price_title'];
		
		$taxonomies						= isset($instance['taxonomies']) ? $instance['taxonomies'] : array();
		$taxonomies_html				= array();
		$taxonomies_counter				= 0;
		
		$taxonomies_objects				= get_object_taxonomies('product', 'objects');
		
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
					
				}
			}
		}
		
		?>
			<?php wp_enqueue_style( ADVANCED_WOOCOMMERCE_PRODUCTS_FILTER_PLUGIN_NAME . '-admin-style' ); ?>

			<script>
				var taxonomiesField	= <?php echo json_encode($this->get_field_name('taxonomies')); ?>;
				var taxonomiesNum	= <?php echo json_encode($taxonomies_counter) ?>;
				
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
					
					// Remove taxonomy
					$(".remove-tax").live('click', function() {
						$(this).parent().remove();
					});
				});
			</script>
			
			<p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>
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
		
		return $instance;
	}
	
	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
		
		if ( ! is_post_type_archive('product') && ! is_tax( get_object_taxonomies('product') ) )
			return;
			
		// Declare filter values
		$taxonomy			= get_query_var('taxonomy');		
		$term_id			= get_queried_object_id();
		$min_price			= null;
		$max_price			= null;
		$min_handle_price	= null;
		$max_handle_price	= null;
		$categories			= array();
		$taxonomies			= array();
		$products			= array();

		/**
		 * awpf_init_categories
		 *
		 * @hooked awpf_init_categories - 10
		 * @hooked awpf_init_taxonomies - 20
		 */
		do_action( 'awpf_init_categories', $instance['show_categories_menu'], $categories );
		do_action( 'awpf_init_taxonomies', $instance['taxonomies'], $taxonomies );
		
		/**
		 * Initiate $categories as an array of arrays (product categories)
		 *
		 * $categories structure:
		 * ======================
		 * $categories[ {category parent ID} ][][0]				=> category ID
		 * $categories[ {category parent ID} ][][1]				=> category link
		 * $categories[ {category parent ID} ][][2]				=> number of products associated with this category (including children)
		 * $categories[ {category parent ID} ][][3]				=> whether this category is checked in subcategory filter [1 = true / 0 = false]
		 * $categories[ {category parent ID} ][][4]				=> whether this category is an ancestor of the current category [true / false]
		 */
/*		if ( $instance['show_categories_menu'] ) {
			$args = array(
				'orderby'	=> 'term_order'
			);
			$terms = get_terms('product_cat', $args);

			if ($terms) {
				foreach ($terms as $term) {
					if ( ! array_key_exists($term->parent, $categories) ) {
						$categories[$term->parent] = array();
					}

					$categories[$term->parent][] = array(
						0 => $term->term_id,
						1 => get_term_link($term),
						2 => $term->count,
						3 => $taxonomy == 'product_cat' && ( $term->term_id == $term_id ) ? 1 : 0,
						4 => $taxonomy == 'product_cat' && ( $term->term_id == $term_id || cat_is_ancestor_of($term->term_id, $term_id) )
					);
				}
			}
		}

		/**
		 * Initiate $taxonomies as an array of arrays (taxonomies and terms data)
		 *
		 * $taxonomies structure:
		 * ======================
		 * $taxonomies[ {taxonomy name} ][0]					=> taxonomy filter title
		 * $taxonomies[ {taxonomy name} ][1]					=> number of products associated with this taxonomy
		 * $taxonomies[ {taxonomy name} ][2][ {term ID} ][0]	=> number of products associated with this term
		 * $taxonomies[ {taxonomy name} ][2][ {term ID} ][1]	=> whether this term is checked in taxonomy filter [1 = true / 0 = false]
		 */
/*		if ( $instance['taxonomies'] ) {
			foreach ($instance['taxonomies'] as $tax) {
				// Get taxonomy terms
				$args = array(
					'orderby'	=> 'term_order'
				);
				$terms = get_terms($tax['name'], $args);
				
				if ($terms) {
					$taxonomies[ $tax['name'] ] = array(
						0 => $tax['title'],
						1 => 0,
						2 => array()
					);

					foreach ($terms as $term) {
						$taxonomies[ $tax['name'] ][2][$term->term_id] = array(
							0 => 0,
							1 => 0
						);
					}
				}
			}
		}
*/		
		if ( ! $instance['show_price_filter'] && ( ! $instance['show_categories_menu'] || ! $categories ) && ! $taxonomies )
			return;
			
		/**
		 * 1. Initiate filter values
		 * 2. Initiate $products as an array of arrays (products and terms associated with each product)
		 *
		 * $products structure:
		 * ====================
		 * $products[ {product ID} ][0]							=> product price
		 * $products[ {product ID} ][1]							=> whether this product is displayed according to filter state [1 = true / 0 = false]
		 * $products[ {product ID} ][2][ {taxonomy name} ]		=> array of taxonomy term_id's associated with this product
		 */
		$products = awpf_init_products_filter_values($taxonomy, $term_id, $min_price, $max_price, $min_handle_price, $max_handle_price, $taxonomies);

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
			wp_enqueue_script( ADVANCED_WOOCOMMERCE_PRODUCTS_FILTER_PLUGIN_NAME . '-products-filter' );
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

		
		if ( ! empty($title) )
			echo
				$before_title .
					$title .
					'<div class="loader">' .
						'<img src="' . ADVANCED_WOOCOMMERCE_PRODUCTS_FILTER_PLUGIN_IMAGES_DIR . '/ajax-loader.gif" width="16" height="16" />' .
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
}
add_action( 'widgets_init', create_function('', 'return register_widget("Advanced_WooCommerce_Products_Filter");') );