<?php
/**
 * functions
 * 
 * @author		Beit Hatfutsot
 * @package		prayer
 * @version		1.0
 */

/**
 * email_message
 *
 * @param	$link (string)
 * @return	string
 */
function email_message($link) {

	$message = '';

	ob_start(); ?>

	<html>
		<head>
			<style>
				#wrapper {
					font-family: Arial, Helvetica, sans-serif;
					color: #666;
				}

				#inner {
					font-size: 14px;
					background-color: #fff;
				}

				#disclaimer {
					padding: 10px;
					font-size: 12px;
					background-color: #eee;
				}

				p {
					margin-bottom: 10px;
				}

				.rtl {
					direction: rtl !important;
					text-align: right !important;
				}
			</style>
		</head>
		<body>
			<div id="wrapper" align="left">
				<div id="inner">
					<img src="http://www.bh.org.il/wp-content/themes/bh/images/general/logo-en-big.png" alt="logo" />

					<p>
						Shalom, and thank you for visiting "Hallelujah! Assemble, Pray, Study – Synagogues Past and Present" at the Museum of the Jewish People at Beit Hatfutsot.<br>
						Here is a link to the prayer you selected during your visit: <a href="<?php echo $link; ?>"><?php echo $link; ?></a>.<br><br>
						It was our pleasure having you at the museum.<br><br>
						We hope to see you again soon,<br>
						<strong>The Museum Team</strong>
					</p>

					<p class="rtl">
						שלום,<br>
						תודה רבה שביקרתם בתערוכת "הללו-יה! להתכנס, להתפלל, ללמוד – בית הכנסת אז והיום" במוזיאון העם היהודי בבית התפוצות.<br>
						להלן הלינק לתפילה שבחרתם בזמן הביקור: <a href="<?php echo $link; ?>"><?php echo $link; ?></a>.<br><br>
						שמחנו לארח אתכם במוזיאון.<br><br>
						בברכה,<br>
						<strong>צוות מוזיאון העם היהודי בבית התפוצות</strong>
					</p>
				</div>

				<div id="disclaimer">
					<p>
						You received this message, because a form had been submitted on Beit Hatfutsot website (www.bh.org.il), using this email address.<br>
						If you did not submit a form on the above site, please disregard this message.<br><br>
						This email was automatically generated and sent from a notification-only address. Please do not reply to this message.
					</p>

					<p class="rtl">
						הודעה זו נשלחה, מפני שנשלח טופס באתר בית התפוצות (www.bh.org.il), תוך כדי ציון כתובת דוא"ל זו.<br>
						אם לא שלחת טופס דרך האתר הנ"ל, אין להתייחס להודעה זו.<br><br>
						זוהי הודעה אוטומטית. נא לא להשיב להודעה זו.
					</p>
				</div>
			</div>
		</body>
	</html>

	<?php $message = ob_get_clean();

	// return
	return $message;

}

/**
 * get_pray
 *
 * @param	$url (string) xml url
 * @param	$pray_id (int)
 * @return	(mixed) array of person and pray content or FALSE in case of failure
 */
function get_pray($url, $pray_id) {

	if ( ! $url || ! $pray_id )
		// return
		return false;

	// parse xml
	$xml = simplexml_load_file($url);

	if ( ! $xml || ! $xml->item[$pray_id-1] )
		// return
		return false;

	$pray = array(
		'person'	=> $xml->item[$pray_id-1]->person,
		'prayer'	=> str_replace( "\n", "<br />", $xml->item[$pray_id-1]->prayer )
	);

	// return
	return $pray;

}