<?php

	// change admin footer text
	function BH_footer_text() {
		return "<span id=\"footer-thankyou\">By <a href=\"http://www.htmline.com/\">HTMLine - בניית אתרים</a>.</span>";
	}
	add_action('admin_footer_text', 'BH_footer_text');

?>