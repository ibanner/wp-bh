<?php
/**
 * AWPF Widget frontend
 *
 * @author		Nir Goldberg
 * @package		admin
 * @version		1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists('AWPF_Widget_Front') ) :

class AWPF_Widget_Front {

	private $attributes;

	/**
	 * __construct
	 *
	 * A dummy constructor to ensure AWPF_Widget_Front is only initialized once
	 *
	 * @since		1.0
	 * @param		N/A
	 * @return		N/A
	 */
	function __construct() {

		/* Do nothing here */

	}

	/**
	 * initialize
	 *
	 * The real constructor to initialize AWPF
	 *
	 * @since		1.0
	 * @param		$show_categories_menu (bool) show (true) / hide (false) categories menu
	 * @param		$show_price_filter (bool) show (true) / hide (false) price filter
	 * @param		$price_title (string) price filter title
	 * @param		$tax_list (array) taxonomy names
	 * @return		N/A
	 */
	function initialize( $show_categories_menu, $show_price_filter, $price_title, $tax_list ) {

		// initiate attributes
		$this->attributes = array(
			// queried object
			'taxonomy'				=> get_query_var('taxonomy'),
			'term_id'				=> get_queried_object_id(),

			// filter settings
			'show_categories_menu'	=> $show_categories_menu,
			'show_price_filter'		=> $show_price_filter,
			'price_title'			=> $price_title,
			'tax_list'				=> $tax_list,

			// filter attributes
			'min_price'				=> null,
			'max_price'				=> null,
			'min_handle_price'		=> null,
			'max_handle_price'		=> null,

			// filter content
			'categories'			=> array(),
			'taxonomies'			=> array(),

			// filtered products data
			'products'				=> array()
		);

		// initiate categories attribute
		if ( $show_categories_menu ) {
			$this->init_categories();
		}

		// initiate taxonomies attribute
		if ( $tax_list ) {
			$this->init_taxonomies();
		}

		if ( ! $show_price_filter && ( ! $show_categories_menu || ! $this->attributes['categories'] ) && ! $this->attributes['taxonomies'] )
			return;
			
		/**
		 * 1. initiate filter values
		 * 2. initiate products attribute as an array of arrays (products and terms associated with each product)
		 */
		$this->init_products_filter_values();

		if ( ! $this->attributes['products'] )
			return;

		// display products filter
		$this->display_products_filter();

	}

	/**
	 * get_attribute
	 *
	 * This function will return a value from the attributes array found in AWPF_Widget_Front object
	 *
	 * @since		1.0
	 * @param		$name (string) the attribute name to return
	 * @return		(mixed)
	 */
	private function get_attribute( $name, $default = null ) {

		$arrtibute = awpf_maybe_get( $this->attributes, $name, $default );

		// return
		return $arrtibute;

	}

	/**
	 * set_attribute
	 *
	 * This function will update a value into the attributes array found in AWPF_Widget_Front object
	 *
	 * @since		1.0
	 * @param		$name (string) the attribute name to update
	 * @param		$value (mixed) the attribute value to update
	 * @return		N/A
	 */
	private function set_attribute( $name, $value ) {

		$this->attributes[ $name ] = $value;

	}

	/**
	 * init_categories
	 *
	 * Initiate categories attribute as an array of arrays (product categories)
	 *
	 * categories structure:
	 * =====================
	 * $categories[ {category parent ID} ][][0]				=> category ID
	 * $categories[ {category parent ID} ][][1]				=> category link
	 * $categories[ {category parent ID} ][][2]				=> number of products associated with this category (including children)
	 * $categories[ {category parent ID} ][][3]				=> whether this category is checked in subcategory filter [1 = true / 0 = false]
	 * $categories[ {category parent ID} ][][4]				=> whether this category is an ancestor of the current category [true / false]
	 *
	 * @since		1.0
	 * @param		N/A
	 * @return		N/A
	 */
	private function init_categories() {

		$show_categories_menu = $this->get_attribute( 'show_categories_menu' );

		if ( ! $show_categories_menu )
			return;

		$categories = array();

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

		// initiate categories attribute
		$this->set_attribute( 'categories', $categories );

	}

	/**
	 * init_taxonomies
	 *
	 * Initiate taxonomies attribute as an array of arrays (taxonomies and terms data)
	 *
	 * taxonomies structure:
	 * =====================
	 * $taxonomies[ {taxonomy name} ][0]					=> taxonomy filter title
	 * $taxonomies[ {taxonomy name} ][1]					=> number of products associated with this taxonomy
	 * $taxonomies[ {taxonomy name} ][2][ {term ID} ][0]	=> number of products associated with this term
	 * $taxonomies[ {taxonomy name} ][2][ {term ID} ][1]	=> whether this term is checked in taxonomy filter [1 = true / 0 = false]
	 *
	 * @since		1.0
	 * @param		N/A
	 * @return		N/A
	 */
	private function init_taxonomies() {

		$tax_list = $this->get_attribute( 'tax_list' );

		if ( ! $tax_list )
			return;

		$taxonomies = array();

		foreach ($tax_list as $tax) {

			// get taxonomy terms
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

		// initiate taxonomies attribute
		$this->set_attribute( 'taxonomies', $taxonomies );

	}

	/**
	 * init_products_filter_values
	 * 
	 * Initiate products filter values according to current taxonomy, term ID, price range and taxonomy terms
	 * 
	 * taxonomies structure:
	 * =====================
	 * $taxonomies[ {taxonomy name} ][0]					=> taxonomy filter title
	 * $taxonomies[ {taxonomy name} ][1]					=> number of products associated with this taxonomy
	 * $taxonomies[ {taxonomy name} ][2][ {term ID} ][0]	=> number of products associated with this term
	 * $taxonomies[ {taxonomy name} ][2][ {term ID} ][1]	=> whether this term is checked in taxonomy filter [1 = true / 0 = false]
	 *
	 * products structure:
	 * ===================
	 * $products[ {product ID} ][0]							=> product price
	 * $products[ {product ID} ][1]							=> whether this product is displayed according to filter state [1 = true / 0 = false]
	 * $products[ {product ID} ][2][ {taxonomy name} ]		=> array of taxonomy term_id's associated with this product
	 *
	 * @since		1.0
	 * @param		N/A
	 * @return		N/A
	 */
	private function init_products_filter_values() {

		global $woocommerce;

		// get attributes
		$taxonomy			= $this->get_attribute( 'taxonomy' );
		$taxonomy_term_id	= $this->get_attribute( 'term_id' );

		// get all products related to taxonomy term ID
		$meta_query = $woocommerce->query->get_meta_query();
		
		$args = array(
			'post_type'			=> 'product',
			'posts_per_page'	=> -1,
			'no_found_rows'		=> true,
			'meta_query'		=> $meta_query
		);

		if ($taxonomy && $taxonomy_term_id) {
			$args['tax_query']	= array(
				array(
					'taxonomy'	=> $taxonomy,
					'field'		=> 'id',
					'terms'		=> $taxonomy_term_id
				)
			);
		}
		$query = new WP_Query($args);
		
		// fill in filter values and $products according to products meta data
		global $post;
		
		if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();

			$this->update_filter_by_product( $post );

		endwhile; endif; wp_reset_postdata();
		
		// update price filter handles in first page load
		$this->set_attribute( 'min_handle_price', $this->get_attribute( 'min_price' ) );
		$this->set_attribute( 'max_handle_price', $this->get_attribute( 'max_price' ) );

	}

	/**
	 * update_filter_by_product
	 *
	 * Update products and taxonomies attributes by a single product meta data
	 *
	 * @since		1.0
	 * @param		$post (object) product object
	 * @return		N/A
	 */
	private function update_filter_by_product( $post ) {

		// get attributes
		$min_price		= $this->get_attribute( 'min_price' );
		$max_price		= $this->get_attribute( 'max_price' );
		$taxonomies		= $this->get_attribute( 'taxonomies' );
		$products		= $this->get_attribute( 'products' );
		
		// get product price
		$_product = wc_get_product($post->ID);
		$price = round( $_product->get_price() );
		
		// initiate $products and update product price and product visibility
		$products[$post->ID] = array(
			0 => $price,
			1 => 1
		);
		
		// update price filter
		if ( is_null($min_price) || is_null($max_price) ) {
			$min_price = $max_price = $price;
		} else {
			$min_price = min($price, $min_price);
			$max_price = max($price, $max_price);
		}

		// initiate $products before input taxonomies data
		$products[$post->ID][2] = array();

		// update $taxonomies filter and $products taxonomies
		if ($taxonomies) {

			foreach ($taxonomies as $tax_name => &$tax_data) {

				// initiate $products before input a single taxonomy data
				$products[$post->ID][2][$tax_name] = array();

				// get all particular taxonomy terms associated with this product 
				$p_terms = wp_get_post_terms($post->ID, $tax_name);
				
				if ($p_terms) {

					// update $taxonomies counters
					// increment number of products associated with this taxonomy
					$tax_data[1]++;
					
					foreach ($p_terms as $p_term) {
						// store term ID in $products
						$products[$post->ID][2][$tax_name][] = $p_term->term_id;

						// update $taxonomies counters
						// increment number of products associated with this term
						$tax_data[2][$p_term->term_id][0]++;
					}

				}

			}

		}

		// update attributes
		$this->set_attribute( 'min_price', $min_price );
		$this->set_attribute( 'max_price', $max_price );
		$this->set_attribute( 'taxonomies', $taxonomies );
		$this->set_attribute( 'products', $products );

	}

	/**
	 * display_products_filter
	 *
	 * Display products filter
	 *
	 * @since		1.0
	 * @param		N/A
	 * @return		N/A
	 */
	private function display_products_filter() {

		// get attributes
		$taxonomy				= $this->get_attribute( 'taxonomy' );
		$term_id				= $this->get_attribute( 'term_id' );
		$show_categories_menu	= $this->get_attribute( 'show_categories_menu' );
		$show_price_filter		= $this->get_attribute( 'show_price_filter' );
		$price_title			= $this->get_attribute( 'price_title' );
		$min_price				= $this->get_attribute( 'min_price' );
		$max_price				= $this->get_attribute( 'max_price' );
		$min_handle_price		= $this->get_attribute( 'min_handle_price' );
		$max_handle_price		= $this->get_attribute( 'max_handle_price' );
		$categories				= $this->get_attribute( 'categories' );
		$taxonomies				= $this->get_attribute( 'taxonomies' );
		$products				= $this->get_attribute( 'products' );

		?>

		<script>
			_AWPF_products_filter_taxonomy				= '<?php echo $taxonomy; ?>';
			_AWPF_products_filter_term_id				=  <?php echo $term_id; ?>;
			_AWPF_products_filter_show_categories_menu	=  <?php echo ( $show_categories_menu ) ? 1 : 0; ?>;
			_AWPF_products_filter_show_price_filter		=  <?php echo ( $show_price_filter ) ? 1 : 0; ?>;
			_AWPF_products_filter_min_price				=  <?php echo $min_price; ?>;
			_AWPF_products_filter_max_price				=  <?php echo $max_price; ?>;
			_AWPF_products_filter_min_handle_price		=  <?php echo $min_handle_price; ?>;
			_AWPF_products_filter_max_handle_price		=  <?php echo $max_handle_price; ?>;
			_AWPF_products_filter_categories			=  <?php echo json_encode( $categories ); ?>;
			_AWPF_products_filter_taxonomies			=  <?php echo json_encode( $taxonomies ); ?>;
			_AWPF_products_filter_products				=  <?php echo json_encode( $products ); ?>;
			_AWPF_products_filter_currency				= '<?php echo html_entity_decode( get_woocommerce_currency_symbol() ); ?>';
		</script>

		<?php
			wp_enqueue_style( 'jquery-ui' );
			wp_enqueue_script( 'jquery-ui' );
			wp_enqueue_script( 'awpf-products-filter' );
		?>

		<?php

		echo '<div class="widgetcontent">';
		
			// Categories menu
			if ( $show_categories_menu && $categories ) {

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
			if ( $show_categories_menu ) {

				echo '<div class="awpf-price-filter">';
					echo ( $price_title ) ? '<div class="awpf-price-filter-title">' . $price_title . '</div>' : '';
					
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
											echo '<input type="checkbox" name="' . $tax_name . '[]" id="' . $term_id . '" value="' . $term_id . '" />' . $term_name . ' <span class="count">(' . $term_data[0] . ')</span>';
										echo '</label>';
									}
								}
							echo '</div>';
						echo '</div>';
					}
				}

			}
			
		echo '</div>';

	}

}

/**
 * awpf_widget_front
 *
 * This function initialize AWPF_Widget_Front
 *
 * @since	1.0
 * @param	N/A
 * @return	(object)
 */
function awpf_widget_front() {

	global $awpf_widget_front;

	if( ! isset($awpf_widget_front) ) {

		$awpf_widget_front = new AWPF_Widget_Front();

	}

	return $awpf_widget_front;

}

// initialize
awpf_widget_front();

endif; // class_exists check