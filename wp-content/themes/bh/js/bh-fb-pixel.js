/*
 BH_FB_onAddToCart
 
 Add AddToCart event to Facebook Pixel
 
 Called as part of the following processes:
 1. Product Single Page Template - Add to Cart button
 
 @param		sku				String		product SKU
 @param		name			String		product name
 @param		category		String		product category which this product belongs to
 @param		value			Float		product price
 @param		currency		String		price currency (USD/ILS)
 */
function BH_FB_onAddToCart(sku, name, category, value, currency) {
	fbq('track', 'AddToCart', {
		content_name: name, 
		content_category: category,
		content_ids: ['' + sku + ''],
		content_type: 'product',
		value: value,
		currency: currency
	});
}