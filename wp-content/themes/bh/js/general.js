var $ = jQuery,
	BH_general = {

		params : {

			// general
			api						: js_globals.template_url + '/api/',
			timeout					: 400,

		},

		init : function() {

			// newsletter popup
			BH_general.newsletter_popup();
			
			// footer menu
			BH_general.footer_menu();

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
				// Featured products
				BH_general.shop_featured_products();

				// Products sliders
				BH_general.shop_products_slider();
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
				shop_cart_popup_content		= shop_cart_popup_wrapper.children('.shop-cart-popup-content');
			
			// append 'glyphicon-remove' icon
			shop_cart_popup_content.append('<span class="glyphicon glyphicon-remove"></span>');
			var shop_cart_popup_close		= shop_cart_popup_content.children('.glyphicon-remove');

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
			setTimeout(function() {
				BH_general.set_newsletter_popup('open');
			}, 5000);
			
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
		
		shop_archive : function() {
			
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
			BH_general.shop_products_slider();
			
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
		
		remove_recently_viewed : function(item) {
			
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
		
		recently_viewed_content : function(content) {
			
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
		
		shop_featured_products : function() {

			$('.featured-product-item').each(function() {
				var item		= $(this),
					image		= item.find('.product-item-image a .image'),
					image_hover	= item.find('.product-item-image a .image-hover');

				item.hover(function() {
					if (image_hover.length > 0) {
						image.hide();
						image_hover.show();
					}

					item.addClass('active');
				}, function() {
					if (image_hover.length > 0) {
						image.show();
						image_hover.hide();
					}

					item.removeClass('active');
				});
			});

		},

		shop_products_slider : function() {
			
			var slideshow = $('.products-slider-carousel');
			
			slideshow.cycle();
			$('.products-slider').fadeIn(BH_general.params.timeout);
			
		},
		
		shop_wswu_banners : function() {
			
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

		footer_links : function() {

			// footer alignment
			$('.footer-links .link-box .link').height('auto').setAllToMaxHeight();

		},

		footer_sub_menu_toggle : function(event) {
			var current = event.currentTarget,
				li = $(current).parent(),
				mobile = $('.footer-menu').hasClass('mobile') ? true : false,
				active = li.hasClass('collapsed') ? true : false;

			if (mobile || li.parent().hasClass('sub-menu')) {
				// prevent closing top level footer sub menus for desktop resolution
				li.parent().find('li.menu-item-has-children').removeClass('collapsed');
			}

			if (active) {
				li.removeClass('collapsed').find('li.menu-item-has-children').removeClass('collapsed');
			}
			else {
				li.addClass('collapsed');
			}
		},

		footer_menu : function() {

			// copy parent items with href attribute into their sub menu
			$('.footer-menu li.menu-item-has-children').each(function() {
				if ($(this).children('a').attr('href') && $(this).children('.sub-menu').length) {
					// create cloned element
					var li = $(this).clone();

					// remove id attribute, menu-item-has-children class and sub menu from cloned element
					li.removeAttr('id');
					li.removeClass('menu-item-has-children');
					li.children('.sub-menu').remove();

					// append cloned element to original sub-menu
					li.prependTo($(this).children('.sub-menu'));

					// remove href attribute from original element
					$(this).children('a').removeAttr('href');
				}
			});

			// bind click event to footer menu elements
			$('.footer-menu li.menu-item-has-children > .item-before').bind('click', BH_general.footer_sub_menu_toggle);
			$('.footer-menu li.menu-item-has-children > a').bind('click', BH_general.footer_sub_menu_toggle);

			// bind click event to mobile menu button
			$('.mobile-menu-btn a').click(function() {
				var header_height = $('header').height();
				$('html, body').animate({scrollTop: $('.footer-menu').offset().top - header_height }, 'slow');
			});

		},

		loaded : function() {
			
			// shop cart popup
			BH_general.shop_cart_popup();

			$(window).resize(BH_general.alignments).resize();
			
		},

		alignments : function() {
			
			// top menu
			BH_general.top_menu();

			// footer links
			BH_general.footer_links();

			// reinit products slider
			$('.products-slider-carousel').cycle('reinit');

			// close all footer sub menus
			$('.footer-menu li.menu-item-has-children').removeClass('collapsed');

			// javascript media queries
			if (matchMedia) {
				var mq = window.matchMedia("(max-width: 767px)");

				if (mq.matches) {
					// width <= 767
					$('.footer-menu').addClass('mobile');
				}
				else {
					// width > 767
					$('.footer-menu').removeClass('mobile');
					// collapse top level footer sub menus
					$('.footer-menu > ul > li.menu-item-has-children').addClass('collapsed');
				}
			}

		}

	};

// make it safe to use console.log always
(function(a){function b(){}for(var c="assert,count,debug,dir,dirxml,error,exception,group,groupCollapsed,groupEnd,info,log,markTimeline,profile,profileEnd,time,timeEnd,trace,warn".split(","),d;!!(d=c.pop());){a[d]=a[d]||b;}})
(function(){try{console.log();return window.console;}catch(a){return (window.console={});}}());

$(BH_general.init);
$(window).load(BH_general.loaded);