var $ = jQuery,
	BH_general = {

		params : {

			// general
			template 				: js_globals.template_url,
			api						: js_globals.template_url + '/api/',
			timeout					: 400,

		},

		init : function() {

			// shop cart popup
			BH_general.shop_cart_popup();
			
			// newsletter popup
			BH_general.newsletter_popup();
			
			// faqs
			$('.faqs li .question').click(function() {
				$(this).toggleClass('expanded');
				$(this).next().next().slideToggle(BH_general.params.timeout);
			});
			
			// embedded video - responsive treatment
			$('.page-content').find('iframe, object, embed').each(function() {
				if ( $(this).attr('name') == 'chekout_frame' || $(this).attr('name') == 'pelecard_frame' )
					return;
					
				var src = $(this).attr('src');
				
				$(this).wrap("<div class='flex-video'></div>");
				
				// add some usefull attributes
				if (src.indexOf('youtube.com') >= 0) {
					src = src.concat('/?showinfo=0&autohide=1&rel=0&wmode=transparent');
					$(this).attr('src', src);
					$(this).attr('wmode', 'Opaque');
				}
			});
			
			// jQuery extentions
			$.fn.setAllToMaxHeight = function() {
				return this.height( Math.max.apply(this, $.map(this, function(e) { return $(e).height() })) );
			}
			
			// shop - homepage
			if ( $('body').hasClass('archive') && $('body').hasClass('woocommerce') ) {
				BH_general.shop_related_products();
			}
			
			// shop - archive
			var classes = ['tax-product_cat', 'tax-occasion', 'tax-artist', 'search'];
			for (var i=0 ; i<classes.length ; i++) {
				if ( $('body').hasClass(classes[i]) ) {
					BH_general.shop_archive();
					break;
				}
			}
			
			// shop - single product
			if ( $('body').hasClass('single-product') ) {
				BH_general.shop_single_product();
			}
			
			// shop - why shop with us
			if ( $('body').hasClass('page-template-shop-why-shop-with-us') ) {
				BH_general.shop_wswu_banners();
			}
			
			// shop footer alignment
			$('.shop-footer .link-box').setAllToMaxHeight();
			
		},

		top_menu : function() {
			$('nav.menu ul.nav > li').each(function() {
				var li_width			= $(this).width(),
					item_before			= $(this).children('.item-before'),
					sub_menu			= $(this).children('.sub-menu'),
					sub_menu_width		= sub_menu.width();

				item_before.css('border-left-width', li_width/2);
				item_before.css('border-right-width', li_width/2);
				item_before.removeClass('disable');

				sub_menu.css('left', (li_width-sub_menu_width)/2);
			});
		},
		
		shop_cart_popup : function() {
			
			var shop_cart_popup_wrapper		= $('header .shop-cart-header-mid-popup'),
				shop_cart_popup_btn			= shop_cart_popup_wrapper.children('.shop-cart-popup-btn'),
				shop_cart_popup_content		= shop_cart_popup_wrapper.children('.shop-cart-popup-content'),
				shop_cart_popup_close		= shop_cart_popup_wrapper.find('.glyphicon-remove');
			
			// bind click events
			shop_cart_popup_btn.click(function() {
				shop_cart_popup_content.toggle();
			});
			
			shop_cart_popup_close.click(function() {
				shop_cart_popup_content.hide();
			});

		},
		
		newsletter_popup : function() {
			
			var newsletter_popup_wrapper	= $('header .newsletter-popup'),
				newsletter_popup_btn		= newsletter_popup_wrapper.children('.newsletter-popup-btn'),
				newsletter_popup_close		= newsletter_popup_wrapper.find('.glyphicon-remove');
			
			// bind click events
			newsletter_popup_btn.click(function() {
				$(this).next().toggle();
				$(this).children('button').toggleClass('active');
				
				// reset newsletter popup expiry
				BH_general.set_newsletter_popup('set');
			});
			
			newsletter_popup_close.click(function() {
				$(this).parent().hide();
				newsletter_popup_btn.children('button').removeClass('active');
				
				// reset newsletter popup expiry
				BH_general.set_newsletter_popup('set');
			});
			
			// popup newsletter
			BH_general.set_newsletter_popup('open');
			
		},
		
		set_newsletter_popup : function(action) {
			
			var newsletter_popup_wrapper	= $('header .newsletter-popup'),
				newsletter_popup_btn		= newsletter_popup_wrapper.children('.newsletter-popup-btn'),
				newsletter_popup_content	= newsletter_popup_wrapper.children('.newsletter-popup-content');
			
			$.ajax({
				
				url		: BH_general.params.api + 'newsletter-popup.php',
				type	: 'POST',
				data	: {
					action		: action
				},
				error: function() {
					return false;
				},
				success: function(result) {
					var r = JSON.parse(result);
					if (r.status == 0) {
						if (action == 'open' && r.operation == 'popup' && !newsletter_popup_btn.children('button').hasClass('active')) {
							newsletter_popup_content.show();
							newsletter_popup_btn.children('button').addClass('active');
						}
						
						return true;
					}
					
					return false;
				}
				
			});
			
		},
		
		shop_archive	: function() {
			
			// recently viewed products - show recently viewed
			BH_general.show_recently_viewed();
			
		},
		
		shop_single_product	: function() {
			
			// recently viewed products - add product to recently viewed
			BH_general.add_recently_viewed();
			
			// single product images
			$('#gallery-navigator li').click(function() {
				if ($(this).hasClass('active'))
					return;
					
				var	main_image = $('#gallery-main-item'),
					li = $(this);
					current_attachment = li.attr('data-thumbnail');
					
				// mark li as active
				$('#gallery-navigator li').removeClass('active');
				li.addClass('active');
				
				main_image.stop().fadeOut(BH_general.params.timeout);
				
				setTimeout(function() {
					main_image.html(_BH_product_attachments[current_attachment]['shop_single']['img']).fadeIn(BH_general.params.timeout);
					
					var ez = $('#gallery-main-item').data('elevateZoom');
					ez.swaptheimage(_BH_product_attachments[current_attachment]['shop_single']['src'], _BH_product_attachments[current_attachment]['full']['src']);
				}, BH_general.params.timeout);
			});
			
			// single product main image zoom
			$('#gallery-main-item').elevateZoom({
				zoomType			: 'inner',
				cursor				: 'crosshair',
				zoomWindowFadeIn	: 500,
				zoomWindowFadeOut	: 500,
				easing				: true
			});
			
			// related products
			BH_general.shop_related_products();
			
		},
		
		show_recently_viewed : function() {
			
			$('.recently-products-slider-placeholder').hide();
			
			$.ajax({
				
				url		: BH_general.params.api + 'recently-viewed.php',
				type	: 'POST',
				data	: {
					action		: 'show'
				},
				error: function() {
					return false;
				},
				success: function(result) {
					if (!result.length)
						return false;
						
					BH_general.recently_viewed_content(result);
					$('.recently-products-slider-placeholder').fadeIn(BH_general.params.timeout);
					
					return true;
				}
				
			});
			
		},
		
		add_recently_viewed : function() {
			
			// find post ID (presented in body className as 'postid-x')
			var postid = '';
			
			var classList = $('body').attr('class').split(/\s+/);
			$.each(classList, function(index, item) {
				if (item.indexOf('postid') >= 0) {
					postid = item.substr(item.indexOf('-') + 1);
				}
			});
			
			if (!postid)
				return;
				
			$('.recently-products-slider-placeholder').hide();
			
			$.ajax({
				
				url		: BH_general.params.api + 'recently-viewed.php',
				type	: 'POST',
				data	: {
					action		: 'add',
					postid		: postid
				},
				error: function() {
					return false;
				},
				success: function(result) {
					if (!result.length)
						return false;
						
					BH_general.recently_viewed_content(result);
					$('.recently-products-slider-placeholder').fadeIn(BH_general.params.timeout);
					
					return true;
				}
				
			});
			
		},
		
		remove_recently_viewed	: function(item) {
			
			var postid = item.attr('data-postid');
			
			if (!postid)
				return;
				
			$('.recently-products-slider-placeholder').fadeOut(BH_general.params.timeout);
			
			$.ajax({
				
				url		: BH_general.params.api + 'recently-viewed.php',
				type	: 'POST',
				data	: {
					action		: 'remove',
					postid		: postid
				},
				error: function() {
					return false;
				},
				success: function(result) {
					var slideshow = $('.recently-products-slider');
					
					// destroy slideshow
					slideshow.cycle('destroy');
					
					if (!result.length) {
						// empty result
						slideshow.html('');
						
						return false;
					}
					else {
						BH_general.recently_viewed_content(result);
						$('.recently-products-slider-placeholder').fadeIn(BH_general.params.timeout);
						
						return true;
					}
				}
				
			});
			
		},
		
		recently_viewed_content	: function(content) {
			
			var slideshow = $('.recently-products-slider');
			
			var	regex = /<li/gm,
				visible_products = content.match(regex);
				
			visible_products = (visible_products) ? visible_products.length : 0;
			
			if (visible_products <= 7) {
				$('#recently-products-slider-prev, #recently-products-slider-next').css('visibility', 'hidden');
			}
			else {
				visible_products = 7;
				$('#recently-products-slider-prev, #recently-products-slider-next').css('visibility', 'visible');
			}
			
			slideshow.attr('data-cycle-carousel-visible', visible_products);
			slideshow.html(content);
			slideshow.cycle();
			
			// recently viewed products - remove product from recently viewed
			$('.recently-products-slider').on('click', '.glyphicon-remove', function(e) {
				BH_general.remove_recently_viewed($(this).parent());
				
				return false;
			});
			
		},
		
		shop_related_products	: function() {
			
			var slideshow = $('.related-slider');
			
			slideshow.cycle();
			$('.related-slider-placeholder').fadeIn(BH_general.params.timeout);
			
		},
		
		shop_wswu_banners	: function() {
			
			var banners		= $('.col-shop-wswu').length,
				fadeSpeed	= 1000,		// fadeIn time
				delayTime	= 1250,		// delay before transform banner to active
				gap			= 0;		// gap between banners exposure
			
			for (var i=0 ; i<banners ; i++) {
				setTimeout(function(index) {
					$('.col-shop-wswu:eq('+index+')').find('.image-wrapper').fadeIn(fadeSpeed);
				}, i*(fadeSpeed+delayTime+gap), i);
				
				setTimeout(function(index) {
					$('.col-shop-wswu:eq('+index+')').addClass('active');
				}, i*(fadeSpeed+delayTime+gap)+fadeSpeed+delayTime, i);
			}
			
		},
		
		loaded : function() {
			
			$(window).resize(BH_general.alignments).resize();
			
		},

		alignments : function() {
			
			// top menu
			BH_general.top_menu();
			
		}
		
	};

// make it safe to use console.log always
(function(a){function b(){}for(var c="assert,count,debug,dir,dirxml,error,exception,group,groupCollapsed,groupEnd,info,log,markTimeline,profile,profileEnd,time,timeEnd,trace,warn".split(","),d;!!(d=c.pop());){a[d]=a[d]||b;}})
(function(){try{console.log();return window.console;}catch(a){return (window.console={});}}());

$(BH_general.init);
$(window).load(BH_general.loaded);