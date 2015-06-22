(function(document, window, $) {
'use strict';

var MIN_ITEMS = 1,
	MAX_ITEMS = 4;

// handle adding / removing items
var itemHandler = {

	items: [],

	set itemCount(val) {

		if (val >= MIN_ITEMS && val <= MAX_ITEMS) {
			this._itemCount = val;
			this.setItemCount(this._itemCount);
		}
	},

	get itemCount() {
		return this._itemCount;
	},

	setItemCount: function(newCount) {
		 
		for (var i = 0; i < newCount; i++) {
			this.items[i].show();
		}

		for (var i = newCount; i < this.items.length; i++) {
			this.items[i].find('input').val('');
			this.items[i].hide();
		}

		if (newCount == MAX_ITEMS ) {
			$('#add').addClass('inactive');
		}
		else if (newCount == MIN_ITEMS ) {
			$('#remove').addClass('inactive');
		}
		else if (newCount == MAX_ITEMS - 1) {
			$('#add').removeClass('inactive');
		}

		else if (newCount == MIN_ITEMS + 1) {
			$('#remove').removeClass('inactive');
		}
	}
};

$(function() {

	// itemHandler init
	var items = $('.item');
	
	for (var i = 0; i < items.length; i++ ) {
		itemHandler.items[i] = $( items[i] ); 
	}

	itemHandler.itemCount = 1;
	window.itemHandler = itemHandler;

	$('#add').click(function() {
		itemHandler.itemCount++;
	});

	$('#remove').click(function() {
		itemHandler.itemCount--;
	});
});

})(document, window, jQuery); 