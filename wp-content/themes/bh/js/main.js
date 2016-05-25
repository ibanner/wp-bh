var $ = jQuery,
	BH_main = {

		params	: {
			
			slider_height	: 216
			
		},

		init	: function() {
			
			/**********/
			/* slider */
			/**********/
			
			BH_main.set_slider_height();
			
			$('.categories-filter ul li').click(function() {
				BH_main.switch_event_category($(this));
			});
			
			// jQuery extentions
			$.fn.setAllToMaxHeight = function() {
				return this.height( Math.max.apply(this, $.map(this, function(e) { return $(e).height() })) );
			}

		},
		
		set_slider_height	: function() {
			
			$('.events-slider .event-meta').setAllToMaxHeight();
			
			var height = BH_main.params.slider_height + $('.events-slider .event-meta').height();
			$('.events-slider-placeholder').height(height);
			$('.events-slider .event-item').height(height);
			
		},
		
		switch_event_category	: function(li) {
			
			if (li.hasClass('active'))
				return;
			
			var category = li.attr('category');
			
			if (category) {
				// fadeout slider
				$('.events-slider').fadeOut(BH_general.params.timeout);
				
				// mark filter as active
				$('.categories-filter ul li').removeClass('active');
				li.addClass('active');
				
				setTimeout(function() {
					// reinitialize slider
					var slideshow = $('.cycle-slideshow');
					var visible_events = _BH_events[category].length;
					visible_events = (visible_events < 5) ? visible_events : 5;
					
					slideshow.cycle('destroy');
					slideshow.attr('data-cycle-carousel-visible', visible_events);
					slideshow.html(_BH_events[category]);
					slideshow.cycle();

					// fadein slider
					$('.events-slider').fadeIn(BH_general.params.timeout);
				}, BH_general.params.timeout);

				setTimeout(function() {
					// set slider height
					BH_main.set_slider_height();
				}, BH_general.params.timeout * 1.2);
			}
			
		},

		/**
		 * load_opening_hours_msg
		 *
		 * Load and display opening hours message
		 *
		 * @since		1.0
		 * @param		N/A
		 * @return		N/A
		 */
		load_opening_hours_msg : function() {

			$.ajax({

				url		: js_globals.ajaxurl,
				type	: 'POST',
				data	: {
					action		: 'opening_hours_msg',
					wpml_lang	: js_globals.wpml_lang
				},
				success: function(result) {

					result = JSON.parse(result);

					// display message
					if (result.status == '0') {
						$('.contact-details-layout-opening-hours-message').html(result.msg);
					}

					// return
					return true;

				},
				error: function(result) {

					// return
					return false;

				}

			});

		},

		loaded	: function() {
			
			// $(window).resize(BH_main.alignments).resize();

			// opening hours message
			BH_main.load_opening_hours_msg();

		},

		alignments	: function() {}

	};

// make it safe to use console.log always
(function(a){function b(){}for(var c="assert,count,debug,dir,dirxml,error,exception,group,groupCollapsed,groupEnd,info,log,markTimeline,profile,profileEnd,time,timeEnd,trace,warn".split(","),d;!!(d=c.pop());){a[d]=a[d]||b;}})
(function(){try{console.log();return window.console;}catch(a){return (window.console={});}}());

$(BH_main.init);
$(window).load(BH_main.loaded);	