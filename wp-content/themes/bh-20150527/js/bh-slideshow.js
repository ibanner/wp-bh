(function (window, document) {
'use strict';

var $ = jQuery;

var bh_slideshow = {

	_index: 0,

	_cycleId: null,

	_active: false,

	_sliding: false,

	container: null,

	descriptionContainer: null,

	interval: 6000,

	slideTimeout: 1200,

	activeDesc: false,

	injectDesc: function() {
		var self = this;

		if (self.items[self._index]) {

			self.descriptionContainer.find('.desc-link').each(function() {
				$(this).attr('href', self.items[self._index].link);
			});

			self.descriptionContainer.find('#category').each(function() {
				$(this).html(self.items[self._index].category);
			});

			self.descriptionContainer.find('#title').each(function() {
				$(this).html(self.items[self._index].title);
			});

			self.descriptionContainer.find('#date').each(function() {
				$(this).html(self.items[self._index].date_html);
			});

			self.descriptionContainer.find('#text').each(function() {
				$(this).html(self.items[self._index].text);
			});
		}
	},

	setSliding: function() {
		var self = this;

		this._sliding = true;
		setTimeout(function() {
			self._sliding = false;
		}, this.slideTimeout);
	},

	isSliding: function() {
		return this._sliding;
	},

	slideDown: function() {
		if (!this.isSliding()) {
			this.setSliding();
			this.slideToIndex(this._index + 1);
		}
	},

	slideUp: function() {
		if (!this.isSliding()) {
			this.setSliding();
			this.slideToIndex(this._index - 1);
		}
	},

	slideToIndex: function(index) {
		var self = this,
			new_index,
			imageHeight = this.container.height();

		this.container.removeClass('slides-reset');

		this.container.css('top', (-1 * index * imageHeight - imageHeight).toString() + 'px');

		// reset slideshow to top after last slide
		if (index ===  this.itemsCount) {
			new_index = 0;
			setTimeout(function() {
				self.container.addClass('slides-reset');
				self.container.css('top', (-1 * imageHeight).toString() + 'px');
			}, 1200);
		}
		// reset slideshow to bottom
		else if (index === -1) {
			new_index = this.itemsCount - 1;
			setTimeout(function() {
				self.container.addClass('slides-reset');
				self.container.css('top', (-1 * self.itemsCount * imageHeight).toString() + 'px');
			}, 1200);
		}
		else {
			new_index = index;
		}
		
		// highlight item in mini gallery
		$('#mini-gallery').find('.mini-gallery-item-wrapper').each(function() {
			if ( $(this).attr('index') == self._index.toString() ) {
				$(this).removeClass('active-item');
			}
			else if ( $(this).attr('index') == new_index.toString() ) {
				$(this).addClass('active-item');	
			}
		});

		this._index = new_index;

		// inject description text
		$('#description-cell').addClass('description-cell-move');

		// change max-height attribute in order to trigger transition
		if (!self.activeDesc) self.descriptionContainer.addClass('ad-hoc');
		
		setTimeout(function() {
			$('#description-cell').removeClass('description-cell-move');
			self.descriptionContainer.removeClass('ad-hoc');
			self.injectDesc();
		}, 800);
	},

	isActive: function() {
		return this._active;
	},

	go: function() {
		var self = this;

		if (!this.isActive()) {
			
			this._cycleId = setInterval(function() {
				self.slideDown();
			}, self.interval);
			
			this._active = true;
		}
	},

	stop: function() {
		var self = this;

		clearInterval(self._cycleId);
	
		this._active = false;
	},

	showDesc: function() {
		var self = this;

		this.stop();
		this.descriptionContainer.addClass('description-show');
		this.miniGalleryContainer.addClass('mini-gallery-show');
		setTimeout(function() {
			if (self.activeDesc) {
				$('#up-arrow').addClass('up-arrow-show');
				$('#down-arrow').addClass('down-arrow-show');
			}
		}, 600);
		this.activeDesc = true;
	},

	hideDesc: function() {
		$('#up-arrow').removeClass('up-arrow-show');
		$('#down-arrow').removeClass('down-arrow-show');
		this.descriptionContainer.removeClass('description-show');
		this.miniGalleryContainer.removeClass('mini-gallery-show');
		this.go();
		this.activeDesc = false;
	},

};

$(function() {

	function isInside(element) {
		if ( ($(element).parents('#slideshow-wrapper')).length === 0 ) {
			return false;
		}
		else {
			return true;
		}
	}

	// init	
	bh_slideshow.container = $('#slides');
	bh_slideshow.descriptionContainer = $('#description-wrapper');
	bh_slideshow.miniGalleryContainer = $('#mini-gallery');
	bh_slideshow.itemsCount = $('#slides img').length - 2;
	bh_slideshow.items = window.items || {};

	// set events
	$('#slideshow-wrapper').mouseover(function(event) {
		if ( !( isInside(event.toTarget) || isInside(event.relatedTarget) ) ) {
			bh_slideshow.showDesc();
		}
	});

	$('#slideshow-wrapper').mouseout(function(event) {
		if ( !( isInside(event.toTarget) || isInside(event.relatedTarget) ) ) {
			bh_slideshow.hideDesc();
		}
	});

	$('#mini-gallery .mini-gallery-item-wrapper').click(function() {
		bh_slideshow.slideToIndex( parseInt( $(this).attr('index') ) );
	});

	$('#up-arrow').click(function() {
		bh_slideshow.slideUp();
	});

	$('#down-arrow').click(function() {
		bh_slideshow.slideDown();
	});

	$(window).on('keydown', function (event) {

		bh_slideshow.activeDesc || bh_slideshow.stop();
		if (event.keyCode == '40') {
			event.preventDefault();
			bh_slideshow.slideDown();
		} 
		else if (event.keyCode == '38') {
			event.preventDefault();
			bh_slideshow.slideUp();
		}
		bh_slideshow.activeDesc || bh_slideshow.go();
	});

	bh_slideshow.injectDesc();
	bh_slideshow.go();

	$(window).resize(function() {
		bh_slideshow.slideToIndex(bh_slideshow._index);
	});

	$(window).focus(function() {
    	bh_slideshow.go();
	});

	$(window).blur(function() {
    	bh_slideshow.stop();
	});

	window.bh_slideshow = bh_slideshow;
});

})(window, document);
