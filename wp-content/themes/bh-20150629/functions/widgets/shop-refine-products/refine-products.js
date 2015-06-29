jQuery( function( $ ) {
	
	// price filter
	$('#price-filter-slider').slider({
		animate	: true,
		range	: true,
		min		: _BH_refine_products_min_price,
		max		: _BH_refine_products_max_price,
		values	: [ _BH_refine_products_min_handle_price, _BH_refine_products_max_handle_price ],
		slide	: function(event, ui) {
			$('#price-filter-amount').val( _BH_refine_currency + ui.values[0] + " - " + _BH_refine_currency + ui.values[1] );
		},
		change	: function( event, ui ) {
			price_slider_change(ui.values[0], ui.values[1]);
		}
	});
	
	$('#price-filter-amount').val( _BH_refine_currency + $('#price-filter-slider').slider('values', 0) + " - " + _BH_refine_currency + $('#price-filter-slider').slider('values', 1) );
	
	// taxonomy filters
	$('.tax-filter .tax-terms input').change(function() {
		map_taxonomy_terms();
	});
	
});

/*
 price_slider_change
 
 Update price range after slider change event
 
 params:
 min		int		minimum handle price
 max		int		maximum handle price
 */
function price_slider_change(min, max) {
	
	_BH_refine_products_min_handle_price = min;
	_BH_refine_products_max_handle_price = max;
	
	map_taxonomy_terms();
	
}

/*
 map_taxonomy_terms
 
 Map taxonomy filters before providing a refine
 */
function map_taxonomy_terms() {
	
	var taxonomy_filters = $('.tax-filter');
	
	taxonomy_filters.each(function() {
		var taxonomy_filter_class_list = $(this).attr('class').split(/\s+/),
			terms = $(this).children('.tax-terms');
		
		// get taxonomy name
		taxonomy_name = '';
		$.each( taxonomy_filter_class_list, function(index, item) {
			if ( item.indexOf('tax-filter-') >= 0 ) {
				taxonomy_name = item.substring(11);
			}
		});
		
		// set checked terms
		terms.find('input').each(function() {
			var term_id = $(this).attr('id');
			_BH_refine_products_taxonomies[taxonomy_name][2][term_id][2] = ($(this).is(':checked')) ? 1 : 0; 
		});
	});
	
	refine_products();
	
}

/*
 refine_products
 
 Make an AJAX call to an API file in order to get filtered data.
 Refresh products grid and product filters
 */
function refine_products() {
	
	var loader = $('.Shop_Refine_Products .loader');
	
	loader.show();
	
	$.ajax({
		
		url		: _BH_refine_products_api,
		type	: 'POST',
		data	: {
			taxonomy			: _BH_refine_products_taxonomy,
			term_id				: _BH_refine_products_term_id,
			min_handle_price	: _BH_refine_products_min_handle_price,
			max_handle_price	: _BH_refine_products_max_handle_price,
			taxonomies			: _BH_refine_products_taxonomies
		},
		error: function() {
			loader.hide();
			
			return false;
		},
		success: function(result) {
			result = JSON.parse(result);
			
			// update products grid
			var posts = result.posts;
			update_products_grid(posts);
			
			// update product filters
			update_product_filters(result);
			
			loader.hide();
			
			return true;
		}
		
	});
	
}

/*
 update_products_grid
 
 params:
 posts		array	array of post IDs to expose
 */
function update_products_grid(posts) {
	
	// hide no posts found message
	$('.not-found').hide();
	
	// hide all products
	$('ul.products li').hide();
	$('ul.products li.delimiter').remove();
	
	// remove special classes from posts
	$('ul.products li').removeClass('first last');
	
	if (posts.length > 0) {
		
		// expose filtered posts
		var i = 0;
		
		$('ul.products li').each(function(index) {
			var postid = parseInt( $(this).attr('data-postid') );
			
			if ( $.inArray(postid, posts) != -1 ) {
				$(this).show();
				
				// rearrange classes and li.delimiter
				if ( 0 == i % 3 ) {
					$(this).addClass('first');
					
					if (i > 0)
						$(this).before('<li class="delimiter"></li>');
				}
				
				if ( 0 == (i+1) % 3 )
					$(this).addClass('last');
					
				i++;
			}
		});
		
	} else {
		
		// no posts found
		$('.not-found').show();
		
	}
	
}

/*
 update_product_filters
 
 params:
 filters					array	array of API results
 
 filters.min_price			int		minimum filter price
 filters.max_price			int		maximum filter price
 filters.min_handle_price	int		minimum filter handle price
 filters.max_handle_price	int		maximum filter handle price
 filters.taxonomies			array	arrays of arrays of terms
 */
function update_product_filters(filters) {
	
	// update price filter
	var min_price 			= parseInt(filters.min_price),
		max_price 			= parseInt(filters.max_price),
		min_handle_price	= parseInt(filters.min_handle_price),
		max_handle_price	= parseInt(filters.max_handle_price);
		
	min_handle_price = (min_handle_price < min_price) ? min_price : min_handle_price;
	max_handle_price = (max_handle_price > max_price) ? max_price : max_handle_price;
	
	$('#price-filter-slider').slider('option', 'change', '');
	
	$('#price-filter-slider').slider('option', {
		values	: [ min_handle_price, max_handle_price ],
		min		: min_price,
		max		: max_price
	});
	
	$('#price-filter-slider').slider('option', {
		change	: function( event, ui ) {
			price_slider_change(ui.values[0], ui.values[1]);
		}
	});
	
	$('#price-filter-amount').val( _BH_refine_currency + $('#price-filter-slider').slider('values', 0) + " - " + _BH_refine_currency + $('#price-filter-slider').slider('values', 1) );
	
	//update taxonomy filters
	$.each( filters.taxonomies, function(taxonomy_name, taxonomy) {
		
		if (taxonomy[1] == 0) {
			// there are no filtered products associated with this taxonomy
			// hide taxonomy filter completely
			$('.tax-filter-' + taxonomy_name).hide();
		} else {
			// there are filtered products associated with this taxonomy
			// expose taxonomy filter
			$('.tax-filter-' + taxonomy_name).show();
			
			$.each( taxonomy[2], function(term_id, term) {
				if (term[1] == 0) {
					// there are no filtered products associated with this term
					// hide taxonomy term
					$('.tax-filter input#' + term_id).parent('label').hide();
				} else {
					// there are filtered products associated with this term
					// update term label and expose term
					$('.tax-filter input#' + term_id).parent('label').find('span').html(term[0] + ' (' + term[1] + ')');
					$('.tax-filter input#' + term_id).parent('label').show();
				}
			});
		}
	});
	
}