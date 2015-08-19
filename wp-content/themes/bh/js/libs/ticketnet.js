
//var RESERVINT_URL = 'http://212.199.108.93/reservint/index.jsp?referentId=3&lang=he'
var POPUP_WIDTH = 900;
var POPUP_HEIGHT = 560;
function openPopup(RESERVINT_URL) {
	var mobile=is_mobile();
	var iphoneYes=checkIfApple(RESERVINT_URL);
//	POPUP_WIDTH = 320;
	if (mobile == true) {
		xWidth = null;
	    if(window.screen != null)
	      xWidth = window.screen.availWidth;
	    if(window.innerWidth != null)
	      xWidth = window.innerWidth;
	    if(document.body != null)
	      xWidth = document.body.clientWidth;
	     if (xWidth != null && xWidth < 480) {
	    	POPUP_WIDTH=xWidth*1;
	
 	    }
	    if(window.innerHeight != null)
	      xHeight =   window.innerHeight;
	    if (xHeight!= null && xWidth < 480){
	    	POPUP_HEIGHT=xHeight*1;
	    }
	} 
    var posn=RESERVINT_URL.indexOf("index"); 
    var RESERVINT_LOGOUT_URL = RESERVINT_URL.substring(0,posn)+"logout.do";
    var width = POPUP_WIDTH;
    var height = POPUP_HEIGHT;
    var opacity = 0.2;
    
    // concatenate google analytics parameters to the url
    var url = RESERVINT_URL;
    ga(function(tracker) {
        var linker = new window.gaplugins.Linker(tracker);
        url = linker.decorate(url);
    });

    var x_sign = "/wp-content/themes/bh/images/general/x.png";
    var documentWidth = $(document).width();
    var documentHeight = $(document).height();
    var windowWidth = $(window).width();
    var windowHeight = $(window).height();
    var scrollTop = $(document).scrollTop();
    var dimmer = $('<div />').css({
        width: documentWidth - 1,
        height: documentHeight - 1,
        zIndex: 1000,
        position: 'absolute',
        top: 0,
        left: 0,
        backgroundColor: "rgba(0,0,0," + opacity.toString() + ")"
    });
    var div = $('<div />').css({
        position: 'absolute',
        top: windowHeight / 2 - height / 2 + scrollTop,
        left: windowWidth / 2 - width / 2,
        width: width,
        height: height,
        borderRadius: 1,
        border: 'solid 1px black',
        zIndex: 1002,
        backgroundColor: "white",
        boxShadow: "1px 1px 1px rgba(0, 0, 0, .4)"
    });
    var close = $('<img />').attr("src", x_sign).css({
        width: 20,
        height: 20,
        position: 'absolute',
        top: 0,
        right: 0,
        zIndex: 1002
    });
    $(close).click(function() {
        $('#randsPopupContainer').attr("src", RESERVINT_LOGOUT_URL);
        setTimeout(function() {
            $('#randsPopupContainer').parent().parent().hide('fast');
            $('#randsPopupContainer').parent().parent().remove();  
        }, 100);
    });
	var iframe = $('<iframe />').css({
		width: width,
		height: height,
		border: 0,
		borderRadius: 10,
		zIndex: 1001,
		overflow: 'hidden',
		}).attr('src', url).attr('DOCTYPE', "text/html").attr("id", "randsPopupContainer");
		$(dimmer).append(div);
		$(div).append(iframe);
		$(div).append(close);
		$('body').append(dimmer);
}

function nextPage(RESERVINT_URL){
	 if (navigator.userAgent.match(/iPhone/i)){
		   var username="";
		   username=getCookie("raz");
		   if (username == "9999") {
		    	setCookie("raz", "raz", 365);
				 var wnd = 	window.open(RESERVINT_URL);
				 setTimeout(function() {
				      wnd.close();
				    }, 350);
				 return true;
		    }
	 } else {
		 var wnd = 	window.open(RESERVINT_URL);
		 setTimeout(function() {
		      wnd.close();
		    }, 350);
	 }
	return false;
}
function checkIfApple(RESERVINT_URL) {
	var xmobile=false;
	if ( (navigator.userAgent.match(/AppleWebKit/) && ! navigator.userAgent.match(/Chrome/)) ||
			navigator.userAgent.match(/iPhone/i) ||
			  navigator.userAgent.match(/iPad/i)
			 || navigator.userAgent.match(/iPod/i)) {
		xmobile=nextPage(RESERVINT_URL);
	}
	return  xmobile;
}

function is_mobile() {
	if( navigator.userAgent.match(/Android/i)
			 || navigator.userAgent.match(/webOS/i)
			 || navigator.userAgent.match(/iPhone/i)
			 || navigator.userAgent.match(/iPad/i)
			 || navigator.userAgent.match(/iPod/i)
			 || navigator.userAgent.match(/BlackBerry/i)
			 || navigator.userAgent.match(/Windows Phone/i)
			 ){
			    return true;
			  }
			 else {
			    return false;
			  }
/*	var agents = ['android', 'webos', 'iphone', 'ipad', 'blackberry'];
	for(i in agents) {
		if(navigator.userAgent.match('/'+agents[i]+'/i')) {
			return true;
		}
	}
	return false; */
}
function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
}
function getCookie(cname) {
    var name = cname + "=";
    try {
        var ca = document.cookie.split(';');
        for(var i=0; i<ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0)==' ') c = c.substring(1);
            if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
        }
      	return "9999";
    } catch(err) {
    	return "9999";
    }
}

