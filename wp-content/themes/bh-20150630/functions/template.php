<?php

	/**
	 * BH_get_contact_details
	 * 
	 * get homepage contact details section
	 * 
	 * @return	string
	 */
	function BH_get_contact_details() {
		ob_start();
		
		get_template_part('views/main/contact-details/contact-details');
		$result = ob_get_contents();
		
		ob_end_clean();
		
		return $result;
	}

?>