<?php
/**
 * The template for displaying the head
 *
 * @author		Beit Hatfutsot
 * @package		wifi-login/views/header
 * @since		1.0.0
 * @version		1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?>

<head>

	<title>Beit Hatfutsot - WiFi login</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

	<link href="css/style.css" rel="stylesheet" type="text/css" />

	<!--[if IE]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<?php
		/**
		 * Google fonts
		 */
		global $globals;

		if ( $globals[ 'google_fonts' ] ) {
			foreach ( $globals[ 'google_fonts' ] as $font ) {
				printf ( "<link href='%s' rel='stylesheet'>", $font );
			}
		}
	?>

</head>