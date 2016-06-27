<?php
/**
 * Facebook send message button (Hebrew)
 *
 * @author 		Beit Hatfutsot
 * @package 	bh/views/footer
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<div id="fb-send-message">
	<script id='fb-send-message-107403835968800'>
	(function(m,a,n,y,c,h,a,t){
		m[h]=t,a=n.getElementsByTagName(y)[0],y = n.createElement(y);
		y.async=1;y.src=(c+'?'+Math.round(+new Date/600000));
		a.parentNode.insertBefore(y,a);
	})(window,0,document,'script','//manychat.com/assets/widgets/fb-send-message.js','mc_config',0,{
		type:'fb-send-message',
		page_id:'107403835968800',
		size:'standard',
		color:'white'
		// Or pass "container" option as an ID of element
		// where button will be rendered
		//,container : 'ELEMENT_ID'
	})
	</script>
</div>