jQuery( function($) {
	// Price filter
	awpf_init_price_slider(_AWPF_products_filter_min_price, _AWPF_products_filter_max_price, _AWPF_products_filter_min_handle_price, _AWPF_products_filter_max_handle_price);
	
	// Taxonomy filters
	$('.awpf-tax-filter .tax-terms input').change(function() {
		awpf_map_taxonomy_terms();
	});
});

/**
 * awpf_init_price_slider
 */
function awpf_init_price_slider(min_handle, max_handle, value0, value1) {
	$('#awpf-price-filter-slider').slider({
		animate	: true,
		range	: true,
		min		: min_handle,
		max		: max_handle,
		values	: [ value0, value1 ],
		slide	: function(event, ui) {
			$('#awpf-price-filter-amount').val( _AWPF_products_filter_currency + ui.values[0] + " - " + _AWPF_products_filter_currency + ui.values[1] );
		},
		change	: function( event, ui ) {
			awpf_price_slider_change(ui.values[0], ui.values[1]);
		}
	});
	
	$('#awpf-price-filter-amount').val( _AWPF_products_filter_currency + $('#awpf-price-filter-slider').slider('values', 0) + " - " + _AWPF_products_filter_currency + $('#awpf-price-filter-slider').slider('values', 1) );
}

/**
 * awpf_price_slider_change
 *
 * Update price range after slider change event
 *
 * @param	int		min		minimum handle price
 * @param	int		max		maximum handle price
 */
function awpf_price_slider_change(min, max) {
	_AWPF_products_filter_min_handle_price = min;
	_AWPF_products_filter_max_handle_price = max;

	awpf_filter_products();
}

/**
 * awpf_map_taxonomy_terms
 *
 * Map taxonomy filters before providing a filtering
 */
function awpf_map_taxonomy_terms() {
	var taxonomy_filters = $('.awpf-tax-filter');
	
	taxonomy_filters.each(function() {
		var taxonomy_filter_class_list = $(this).attr('class').split(/\s+/),
			terms = $(this).children('.tax-terms');
		
		// Get taxonomy name
		tax_name = '';
		$.each( taxonomy_filter_class_list, function(index, item) {
			if ( item.indexOf('awpf-tax-filter-') >= 0 ) {
				tax_name = item.substring(16);
			}
		});
		
		// Set checked terms
		terms.find('input').each(function() {
			var term_id = $(this).attr('id');
			_AWPF_products_filter_taxonomies[tax_name][2][term_id][2] = ($(this).is(':checked')) ? 1 : 0; 
		});
	});
	
	awpf_filter_products();
}

/**
 * awpf_filter_products
 *
 * Refresh products filter and products grid
 */
function awpf_filter_products() {
	var loader = $('.Advanced_WooCommerce_Products_Filter .loader');
	
	// Expose loader
	loader.show();

	// Update products filter
	awpf_update_products_filter();

	// Update products grid
	awpf_update_products_grid();
	
	// Hide loader
	loader.hide();
	
	return true;
}

/**
 * awpf_update_products_filter
 */
function awpf_update_products_filter() {
	min_price = null;
	max_price = null;

	// Reset _AWPF_products_filter_taxonomies counters
	$.each( _AWPF_products_filter_taxonomies, function(tax_name, tax_data) {
		tax_data[1] = 0;
		$.each( tax_data[2], function(term_id, term_data) {
			term_data[1] = 0;
		});
	});

	// Update _AWPF_products_filter_products product visibility and _AWPF_products_filter_taxonomies counters
	$.each( _AWPF_products_filter_products, function(product_id, product_data) {
		// Set all product as visible by default
		product_data[1] = 1;

		// Skip products not assosiated with checked taxonomy terms
		$.each(_AWPF_products_filter_taxonomies, function(tax_name, tax_data) {
			$.each( tax_data[2], function(term_id, term_data) {
				// Check if taxonomy term is checked and associated with this product 
				if ( term_data[2] && $.inArray(parseInt(term_id), product_data[2][tax_name]) == -1 ) {
					product_data[1] = 0;
					return false;
				}
			});
			if ( ! product_data[1] )
				return false;
		});

		if ( ! product_data[1] )
			return true;

		// Update minimum and maximum price
		var price = product_data[0];

		if ( ! min_price || ! max_price ) {
			min_price = max_price = price;
		} else {
			min_price = (price < min_price) ? price : min_price;
			max_price = (price > max_price) ? price : max_price;
		}

		// Skip products out of price range
		if ( price < _AWPF_products_filter_min_handle_price || price > _AWPF_products_filter_max_handle_price ) {
			product_data[1] = 0;
			return true;
		}

		// Update _AWPF_products_filter_taxonomies counters
		$.each( product_data[2], function(tax_name, term_ids) {
			if (term_ids.length > 0) {
				// Add 1 in taxonomy level
				_AWPF_products_filter_taxonomies[tax_name][1]++;

				$.each(term_ids, function(i, term_id) {
					// Add 1 in taxonomy term level
					_AWPF_products_filter_taxonomies[tax_name][2][term_id][1]++;
				});
			}
		});
	});

	// Update price filter
	// Use new temporary variables in order to save untouched price filter handles
	min_handle_price = (min_price && _AWPF_products_filter_min_handle_price < min_price) ? min_price : _AWPF_products_filter_min_handle_price;
	max_handle_price = (max_price && _AWPF_products_filter_max_handle_price > max_price) ? max_price : _AWPF_products_filter_max_handle_price;

	// Reinit price slider
	$('#awpf-price-filter-slider').slider('destroy');
	awpf_init_price_slider(min_price, max_price, min_handle_price, max_handle_price);

	// Update taxonomy filters
	$.each( _AWPF_products_filter_taxonomies, function(tax_name, tax_data) {
		if (tax_data[1] == 0) {
			// There are no filtered products associated with this taxonomy
			// Hide taxonomy filter completely
			$('.awpf-tax-filter-' + tax_name).hide();
		} else {
			// There are filtered products associated with this taxonomy
			// Expose taxonomy filter
			$('.awpf-tax-filter-' + tax_name).show();
			
			$.each( tax_data[2], function(term_id, term_data) {
				if (term_data[1] == 0) {
					// There are no filtered products associated with this term
					// Hide taxonomy term
					$('.awpf-tax-filter input#' + term_id).parent('label').hide();
				} else {
					// There are filtered products associated with this term
					// Update term label and expose term
					$('.awpf-tax-filter input#' + term_id).parent('label').find('span').html(term_data[0] + ' (' + term_data[1] + ')');
					$('.awpf-tax-filter input#' + term_id).parent('label').show();
				}
			});
		}
	});
}

/**
 * awpf_update_products_grid
 */
function awpf_update_products_grid() {
	var displayed_products = 0;

	// Hide no posts found message
	$('.not-found').hide();
	
	// Hide all products
	$('ul.products li').hide();
	
	// Expose filtered posts
	$('ul.products li').each(function(index) {
		var postid = parseInt( $(this).attr('data-postid') );
		
		if (_AWPF_products_filter_products[postid][1]) {
			$(this).show();
			displayed_products++;
		}
	});
		
	if ( ! displayed_products ) {
		// Expose no posts found message
		$('.not-found').show();
	}
}