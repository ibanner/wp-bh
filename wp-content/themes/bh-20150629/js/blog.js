var $ = jQuery,
	BH_blog = {

		params : {},

		init : function() {},

		load_comments_count : function(elem) {
			
			var postUrl = $(elem).attr('data-href');
			
			$.ajax({
				
				url		: 'https://graph.facebook.com/?ids=' + postUrl,
				type	: 'GET',
				success: function(result) {
					for (var index in result) {
						var comments = result[index]['comments'];
						break;
					}
					
					if (comments) {
						$(elem).html(comments);
						$(elem).next('span').show();
						$(elem).addClass('active');
					}
					
					return false;
				}
				
			});
			
		},
		
		loaded : function() {
			
			$('.post-comments-count').each(function() {
				BH_blog.load_comments_count(this);
			});
			
			$(window).resize(BH_blog.alignments).resize();

		},

		alignments : function() {}

	};

// make it safe to use console.log always
(function(a){function b(){}for(var c="assert,count,debug,dir,dirxml,error,exception,group,groupCollapsed,groupEnd,info,log,markTimeline,profile,profileEnd,time,timeEnd,trace,warn".split(","),d;!!(d=c.pop());){a[d]=a[d]||b;}})
(function(){try{console.log();return window.console;}catch(a){return (window.console={});}}());

$(BH_blog.init);
$(window).load(BH_blog.loaded);	