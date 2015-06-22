(function(window, document, context, $){
	var url		= context.pluginUrl + '/api/api.php',
		loader	= new Image();
	
	loader.src = context.pluginUrl + '/images/loader.gif';
	//$(loader).css('position', 'absolute');
	//$(loader).css('top', '50%');
	//$(loader).css('left', '50%');
	$(loader).css('display', 'inline-block');
	$(loader).css('height', '20px');
	$(loader).css('width', '20px');
	$(loader).css('vertical-align', 'middle');

	$.fn.serializeObject = function() {
	    var o = {};
	    var a = this.serializeArray();
	    $.each(a, function() {
	        if (o[this.name] !== undefined) {
	            if (!o[this.name].push) {
	                o[this.name] = [o[this.name]];
	            }
	            o[this.name].push(this.value || '');
	        } else {
	            o[this.name] = this.value || '';
	        }
	    });
	    return o;
	};

	function post(data, element) {

		$.post(url, data, function(response) {
			console.log(response)
			reload(response);
		});
	}

	function reload(response) {
		location.reload();
	}

	$('#bh-custom-payment-admin form').submit(function() {
		var data = {
			action	: 'create', 
			form	: $(this).serializeObject()
		}

		$(this).append(loader); 

		post(data, this);

		return false;
	});

	$('#bh-custom-payment-admin .resend').click(function() {
		var email = $(this).parent().parent().find('.customer-email-resend').val(),
			data = {
				action	: 'resend',
				key		: $(this).attr('key'),
				email	: email
			};

		$(this).after(loader);

		post(data);
	});


	$('#bh-custom-payment-admin .delete').click(function() {
		var data = {
				action	: 'delete',
				key		: $(this).attr('key'),
			};

		$(this).after(loader);

		post(data);
	});

})(window, document, context, jQuery);