var $ = jQuery,
	BH_general = {

		params : {

			// general
			template 				: js_globals.template_url,
			api						: js_globals.template_url + '/api/',
			timeout					: 400,

			// mobile menu
			sideslider				: '',		// hamburger
			sel						: '',		// side menu container
			side_menu				: '',		// hamburger click event handler

			mobile_menu_right		: '',		// for RTL will be considered as mobile_menu_left
			mobile_menu_width		: '',
			mobile_menu_level		: 1,
			mobile_menu_ancestors	: []		// active side menus

		},

		init : function() {

			// newsletter top menu
			BH_general.newsletter_top_menu();
			
			// mobile menu
//			BH_general.mobile_menu();
			
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
		
		newsletter_top_menu : function() {
			
			var newsletter_widget			= $('header .newsletter-widget'),
				newsletter_widget_btn		= newsletter_widget.children('.newsletter-widget-btn'),
				newsletter_widget_content	= newsletter_widget.children('.newsletter-widget-content'),
				newsletter_widget_close 	= newsletter_widget_content.children('.glyphicon-remove');
			
			// bind click events
			newsletter_widget_btn.click(function() {
				newsletter_widget_content.toggle();
				$(this).children('button').toggleClass('active');
				
				// reset newsletter popup expiry
				BH_general.newsletter_popup('set');
			});
			
			newsletter_widget_close.click(function() {
				newsletter_widget_content.hide();
				newsletter_widget_btn.children('button').removeClass('active');
				
				// reset newsletter popup expiry
				BH_general.newsletter_popup('set');
			});
			
			// popup newsletter
			BH_general.newsletter_popup('open');
			
		},
		
		newsletter_popup : function(action) {
			
			var newsletter_widget			= $('header .newsletter-widget'),
				newsletter_widget_btn		= newsletter_widget.children('.newsletter-widget-btn'),
				newsletter_widget_content	= newsletter_widget.children('.newsletter-widget-content');
			
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
						if (action == 'open' && r.operation == 'popup' && !newsletter_widget_btn.children('button').hasClass('active')) {
							newsletter_widget_content.show();
							newsletter_widget_btn.children('button').addClass('active');
						}
						
						return true;
					}
					
					return false;
				}
				
			});
			
		},
		
		mobile_menu : function() {
			
			BH_general.params.sideslider = $('[data-toggle=collapse-side]');
			BH_general.params.sel = BH_general.params.sideslider.attr('data-target');
			
			// sideslider click event
			side_menu = function() {
				// disable double click during side menu exposure
				BH_general.params.sideslider.unbind('click', side_menu);
				
				// expose side menu
				$(BH_general.params.sel).toggleClass('in');
				$('.side-menu[menu-level="1"]').toggle();
				
				// expose side column
				$('.side-column').css('width', BH_general.params.mobile_menu_right);
				
				// update menu ancestors
				BH_general.params.mobile_menu_ancestors.push(0);
			}
			BH_general.params.sideslider.bind('click', side_menu);
			
			// set side-menu's "right" value
			$('.side-menu').each(function() {
				var level = $(this).attr('menu-level');
				var right = (level-1)*100;
				
				if ($('body').hasClass('rtl'))
					$(this).css('left', right + '%');
				else
					$(this).css('right', right + '%');
			});
			
			// copy parent items with href attribute into their sub menu
			$('.side-menu .menu-item-has-children').each(function() {
				var li = $(this);
				
				if ( $(this).children('a').attr('href') ) {
					var item = $(this).children('a').attr('item');
					var sub_menu = $('.side-menu[menu-parent="' + item + '"]');
					li.clone().prependTo(sub_menu.children('nav').children('ul'));
					
					// remove href attribute from original element
					$(this).children('a').removeAttr('href');
					
					// remove class 'menu-item-has-children' from cloned element
					sub_menu.find('li.menu-item-' + item).removeClass('menu-item-has-children');
					
					// add class 'cloned-menu-item' to cloned element
					sub_menu.find('li.menu-item-' + item).addClass('cloned-menu-item');
				}
			});
			
			// open side-menu event
			$('.side-menu .menu-item-has-children').click(function(event) {
				var menu = $(this).parents('.side-menu');
				var level = parseInt( menu.attr('menu-level') );
				var item = $(this).children('a').attr('item');
				
				sub_menu = $('.side-menu[menu-parent="' + item + '"]');
				
				// expose sub menu
				sub_menu.show();
				
				BH_general.params.mobile_menu_level = level+1;
				BH_general.mobile_menu_pos(level+1);
				
				// update menu ancestors
				BH_general.params.mobile_menu_ancestors.push(item);
			});
			
			// close side-menu event
			$('.side-menu-top span').click(function(event) {
				var menu = $(this).parent().parent();
				BH_general.hide_side_menu(menu);
			});
			
			// close side-menu by click side column
			$('.side-column').click(function() {
				var menu = $('.side-menu[menu-parent="' + BH_general.params.mobile_menu_ancestors[BH_general.params.mobile_menu_ancestors.length-1] + '"]');
				BH_general.hide_side_menu(menu);
			});
			
		},
		
		hide_side_menu : function(menu) {
			
			if (BH_general.params.mobile_menu_level == 1) {
				$(BH_general.params.sel).toggleClass('in');

				setTimeout(function() {
					// hide side menu
					$('.side-menu[menu-level="1"]').toggle();
					BH_general.params.sideslider.bind('click', side_menu);
					
					// hide side column
					$('.side-column').css('width', 0);
				}, BH_general.params.timeout);
			}
			else {
				BH_general.params.mobile_menu_level--;
				BH_general.mobile_menu_pos(BH_general.params.mobile_menu_level);
				
				setTimeout(function() {
					// hide menu
					menu.hide();
				}, BH_general.params.timeout);
			}
			
			// update menu ancestors
			BH_general.params.mobile_menu_ancestors.pop();
			
		},

		mobile_menu_pos : function(level) {
			
			var right = (-1) * (level-1) * $(window).width() + level * BH_general.params.mobile_menu_right;
			
			if ($('body').hasClass('rtl'))
				$('.navbar .side-collapse').css({"left": right + "px"});
			else
				$('.navbar .side-collapse').css({"right": right + "px"});
				
			$('.navbar .side-collapse').css({"width": BH_general.params.mobile_menu_width + "px"});
			
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
			
			// shop experience
			/*
			toggle_experience = function() {
				// disable double click during experience toggle
				$('#experience-toggle').unbind('click', toggle_experience);
				
				var action = ($('#experience-toggle').hasClass('active')) ? 'close' : 'open';
				BH_general.shop_experience(action);
			};
			BH_general.shop_experience('init');
			*/
			
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
		
		/*
		shop_experience	: function(action) {
			
			var	toggle_btn = $('#experience-toggle'),
				experience = $('#experience');
			
			$.ajax({
				
				url		: BH_general.params.api + 'shop-experience.php',
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
						if (!(action == 'init' && r.new_state == 'inactive')) {
							toggle_btn.removeClass('active inactive');
							toggle_btn.addClass(r.new_state);
							
							experience.slideToggle(BH_general.params.timeout);
						}
						
						setTimeout(function() {
							$('#experience-toggle').bind('click', toggle_experience);
						}, BH_general.params.timeout);
						
						return true;
					}
					
					return false;
				}
				
			});
			
		},
		*/
		
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
			
			/* javascript media queries */
			/*
			if (matchMedia) {
				var mq = window.matchMedia("(max-width: 1199px)");
				mq.addListener(WidthChange);
				WidthChange(mq);
			}
			
			// media query change
			function WidthChange(mq) {
				var win_width,
					site_width,
					scrollbar_width;
				
				if (mq.matches) {
					// fix mobile menu positioning according to body and site sizes
					win_width = $(window).width();
					site_width = $('.navbar .container').width();
					
					BH_general.params.mobile_menu_right = (win_width-site_width) / 2 + 54;
					BH_general.params.mobile_menu_width = win_width - BH_general.params.mobile_menu_right;
				}
				else {
					// reset mobile menu positioning
					BH_general.params.mobile_menu_right = 0;
					BH_general.params.mobile_menu_width = win_width;
				}
				
				// set side column width (mask parent menu in order to prevent clicks)
				if (BH_general.params.mobile_menu_ancestors.length)
					$('.side-column').css('width', BH_general.params.mobile_menu_right);
				
				// set mobile menu positioning
				BH_general.mobile_menu_pos(BH_general.params.mobile_menu_level);
			}
			*/

			// top menu
			BH_general.top_menu();
			
		}
		
	};

// make it safe to use console.log always
(function(a){function b(){}for(var c="assert,count,debug,dir,dirxml,error,exception,group,groupCollapsed,groupEnd,info,log,markTimeline,profile,profileEnd,time,timeEnd,trace,warn".split(","),d;!!(d=c.pop());){a[d]=a[d]||b;}})
(function(){try{console.log();return window.console;}catch(a){return (window.console={});}}());

$(BH_general.init);
$(window).load(BH_general.loaded);