(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

ga('create', 'UA-8676522-1', 'auto');
ga('send', 'pageview');

/*
 Enhanced Ecommerce
 */
ga('require', 'ec');

/*
 BH_EC_onListView
 
 Step 1: Measuring a Product Impression
 Provide product details in a impressionFieldObject
 
 Called from the following lists:
 1. Shop Homepage Sliders
 2. Archive Product Category
 3. Archive Product Search
 4. Product Single Page / Related Products
 
 @param		products		String		json represents an array of product data array
 @param		currency		String		price currency (USD/ILS)
 
 products array contains the following data for each product array: 
 @param		sku				String		product SKU
 @param		name			String		product name
 @param		list			String		list name on which the action is measured
 @param		category		String		product category which this product belongs to
 @param		price			String		product price
 
 @uses		BH_EC_addImpression()
 */
function BH_EC_onListView(products, currency) {
	products = Object.keys(products).map(function(k) { return products[k] });
	
	ga('set', '&cu', currency);
	
	products.forEach(BH_EC_addImpression);
	
	ga('send', 'pageview');
}

/*
 BH_EC_onProductClick
 
 Step 2: Measuring a Product Click
 Provide product details in a productFieldObject
 
 Called from the following Page Templates:
 1. Shop Homepage Sliders (via Product Item Box)
 2. Archive Product Category (via Product Item Box)
 3. Archive Product Search (via Product Item Box)
 4. Product Single Page / Related Products (via Product Item Box)
 5. Recently Viewed (via Recently Viewed Widget)
 6. Mini Cart
 7. Cart / Order Review
 8. Checkout / Order Review
 
 @param		sku				String		product SKU
 @param		name			String		product name
 @param		category		String		product category which this product belongs to
 @param		price			String		product price
 @param		currency		String		price currency (USD/ILS)
 @param		list			String		list name on which the action is measured
 @param		link_type		String		type of link which this action is measured on (Product Image/Product Title/Mini Cart Row)
 @param		product_page	String		product page (reference URL)
 */
function BH_EC_onProductClick(sku, name, category, price, currency, list, link_type, product_page) {
	ga('set', '&cu', currency);
	
	ga('ec:addProduct', {
		'id'		: sku,
		'name'		: name,
		'category'	: category,
		'price'		: price
	});
	ga('ec:setAction', 'click', {'list': list});
	
	// send click with an event, then send user to product page
	ga('send', 'event', 'Single Product', 'click', link_type, {
		hitCallback: function() {
			document.location = product_page;
		}
	});
}

/*
 BH_EC_onProductDetail
 
 Step 3: Measuring a Product Details View
 Provide product details in a productFieldObject
 
 Called from within Product Single Page Template on page load
 
 @param		sku				String		product SKU
 @param		name			String		product name
 @param		category		String		product category which this product belongs to
 @param		price			String		product price
 @param		currency		String		price currency (USD/ILS)
 */
function BH_EC_onProductDetail(sku, name, category, price, currency) {
	ga('set', '&cu', currency);
	
	ga('ec:addProduct', {
		'id'		: sku,
		'name'		: name,
		'category'	: category,
		'price'		: price
	});
	ga('ec:setAction', 'detail');
	
	ga('send', 'pageview');
}

/*
 BH_EC_onUpdateCart
 
 Step 4: Measuring an Addition or Removal from Cart
 Provide product details in a productFieldObject
 
 Called as part of the following processes:
 1. Product Single Page Template - Add to Cart button
 
 @param		sku				String		product SKU
 @param		name			String		product name
 @param		category		String		product category which this product belongs to
 @param		price			String		product price
 @param		currency		String		price currency (USD/ILS)
 @param		quantity		Number		product quantity
 @param		action			String		action type (add/remove)
 */
function BH_EC_onUpdateCart(sku, name, category, price, currency, quantity, action) {
	ga('set', '&cu', currency);
	
	ga('ec:addProduct', {
		'id'		: sku,
		'name'		: name,
		'category'	: category,
		'price'		: price,
		'quantity'	: quantity
	});
	ga('ec:setAction', action);
	
	ga('send', 'event', 'Single Product', 'click', (action == 'add') ? 'Add to Cart' : 'Remove from Cart');
}

/*
 BH_EC_onCheckout
 
 Step 5: Measuring Checkout Process
 Provide each product in cart in a separate productFieldObject
 
 Called from within Checkout Page Template on page load
 
 @param		cart			String		json representing the user's shopping cart
 @param		currency		String		price currency (USD/ILS)
 
 cart array contains the following data for each product array:
 @param		sku				String		product SKU
 @param		name			String		product name
 @param		category		String		product category which this product belongs to
 @param		price			String		product price
 @param		quantity		Number		product quantity
 
 @uses		BH_EC_addProduct()
 */
function BH_EC_onCheckout(cart, currency) {
	cart = Object.keys(cart).map(function(k) { return cart[k] });
	
	ga('set', '&cu', currency);
	
	cart.forEach(BH_EC_addProduct);
	
	ga('ec:setAction','checkout', {'step': 1});
	
	ga('send', 'pageview');
}

/*
 BH_EC_onTransaction
 
 Step 6: Measuring a Transaction
 Provide each product in cart in a separate productFieldObject
 Provide transaction level information in a actionFieldObject
 
 Called from within Thank You Page Template on page load
 
 @param		cart			String		json representing the user's shopping cart
 @param		transaction		String		json representing the transaction level information (order ID, revenue, tax, shipping)
 @param		currency		String		price currency (USD/ILS)
 
 cart array contains the following data for each product array:
 @param		sku				String		product SKU
 @param		name			String		product name
 @param		category		String		product category which this product belongs to
 @param		price			String		product price
 @param		quantity		Number		product quantity
 
 transaction array contains the following data:
 @param		id				String		order ID
 @param		total			String		total revenue
 @param		tax				String		total tax
 @param		shipping		String		total shipping
 
 @uses		BH_EC_addProduct()
 */
function BH_EC_onTransaction(cart, transaction, currency) {
	cart = Object.keys(cart).map(function(k) { return cart[k] });
	transaction = Object.keys(transaction).map(function(k) { return transaction[k] });
	
	ga('set', '&cu', currency);
	
	cart.forEach(BH_EC_addProduct);
	
	ga('ec:setAction', 'purchase', {
		'id'		: transaction[0].id,
		'revenue'	: transaction[0].total,
		'tax'		: transaction[0].tax,
		'shipping'	: transaction[0].shipping
	});
	
	ga('send', 'pageview');
}

/*
 BH_EC_addImpression
 
 Provide product details in a impressionFieldObject
 
 elem array contains the following data:
 @param		sku				String		product SKU
 @param		name			String		product name
 @param		list			String		list name on which the action is measured
 @param		category		String		product category which this product belongs to
 @param		price			String		product price
 */
function BH_EC_addImpression(elem, index, array) {
	ga('ec:addImpression', {
		'id'			: elem['sku']
		//'name'		: elem['name'],
		//'list'		: elem['list'],
		//'category'	: elem['category'],
		//'position'	: index+1,
		//'price'		: elem['price']
	});
}

/*
 BH_EC_addProduct
 
 Provide product details in a productFieldObject
 
 elem array contains the following data:
 @param		sku				String		product SKU
 @param		name			String		product name
 @param		category		String		product category which this product belongs to
 @param		price			String		product price
 @param		quantity		Number		product quantity
 */
function BH_EC_addProduct(elem, index, array) {
	ga('ec:addProduct', {
		'id'		: elem['sku'],
		'name'		: elem['name'],
		'category'	: elem['category'],
		'price'		: elem['price'],
		'quantity'	: elem['quantity']
	});
}