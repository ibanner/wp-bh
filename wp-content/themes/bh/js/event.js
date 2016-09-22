var $ = jQuery,
	BH_event = {

		params	: {
			
			category_id	: '',
			date		: ''
			
		},

		init	: function() {
			
			// category
			$('.event-filter-by-category select').change(function() {
				BH_event.params.category_id = $(this).val();
				
				// set date value from input field
				BH_event.params.date = (BH_event.params.date) ? $('.event-filter-by-date input').val() : '';
				
				BH_event.load_events_list();
			});
			
			// datepicker
			$('.datepicker').datepicker({
				dateFormat		: 'dd/mm/yy',
				showButtonPanel	: true,
				onSelect		: function(datepicker_date) {
					BH_event.params.date = datepicker_date;
					
					// set category_id value from select field
					BH_event.params.category_id = $('.event-filter-by-category select').val();
					
					BH_event.load_events_list();
				}
			});
			
			// jQuery extentions
			$.fn.setAllToMaxHeight = function() {
				return this.height( Math.max.apply(this, $.map(this, function(e) { return $(e).height() })) );
			}

		},
		
		load_events_list	: function() {
			
			$('.event-filters .loader').show();
			
			$.ajax({
				
				url		: BH_general.params.api + 'events.php',
				type	: 'POST',
				data	: {
					event_category	: BH_event.params.category_id,
					event_date		: BH_event.params.date,
					lang			: $('.event-filter-lang').text()
				},
				error: function() {
					$('.event-filters .loader').hide();

					return false;
				},
				success: function(result) {
					$('.event-filters .loader').hide();
					$('.events-list-container').html(result);
					
					return false;
				}
				
			});
			
		},
		
		loaded	: function() {
			
			// $(window).resize(BH_event.alignments).resize();

		},

		alignments	: function() {}

	};

// make it safe to use console.log always
(function(a){function b(){}for(var c="assert,count,debug,dir,dirxml,error,exception,group,groupCollapsed,groupEnd,info,log,markTimeline,profile,profileEnd,time,timeEnd,trace,warn".split(","),d;!!(d=c.pop());){a[d]=a[d]||b;}})
(function(){try{console.log();return window.console;}catch(a){return (window.console={});}}());

$(BH_event.init);
$(window).load(BH_event.loaded);	