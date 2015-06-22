<?php

class CustomPaymentManager {
	private $dbTableName;

	public function __construct($dbTableName) {
		$this->dbTableName = $dbTableName;
	}

	public function getData() {
		global $wpdb;

		return $wpdb->get_results( 'SELECT * FROM ' . $this->dbTableName . ' ORDER BY timeIssued DESC', ARRAY_A );		
	}

	public function getCustomPayment($key) {
		global $wpdb;

	    $data = $wpdb->get_row( "SELECT * FROM " . $this->dbTableName . " WHERE paymentKey='" . $key . "'", ARRAY_A );

	    if ($data && isset($data['paymentKey']) && $data['paymentKey'] !== '') {
	    	return new CustomPayment($data, $this->dbTableName);
	    }

	    return false;
	}

	public function createCustomPayment($data) {
		global $wpdb;

		$created = $wpdb->insert($this->dbTableName, $data);

		return $created ? new CustomPayment($data, $this->dbTableName) : false;
	}
}

class CustomPayment {
	private $dbTableName;
	public $data = array();

	public function __construct($data, $dbTableName) {
		$this->data = $data;
		$this->dbTableName = $dbTableName;
	}

	private function sendMail($to, $subject, $from, $name, $message) {

	    $headers  = 'MIME-Version: 1.0' . "\r\n";
	    $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
	    $headers .= 'From: ' . $name . ' <' . $from . '>' . "\r\n";
	    
	    return @wp_mail($to, $subject, $message, $headers);
	}
	
	private function update($data) {
		global $wpdb;

		$updated = $wpdb->update( $this->dbTableName, $data, array('paymentKey' => $this->data['paymentKey']) );

		if ($updated) {
			$this->data = $wpdb->get_row( "SELECT * FROM " . $this->dbTableName . " WHERE paymentKey='" . $this->data['paymentKey'] . "'", ARRAY_A );
		}

		return $updated;
	}

	private function generateMessage($message_name, $inject) {
		
		$doc = new DOMDocument();
		$doc->loadHTMLFile(CUSTOM_PAYMENT_PLUGIN_DIR . '/messages/' . $message_name . '.html');

		foreach ($inject['tag'] as $tagname => $val) {
			$elements = $doc->getElementsByTagName($tagname);

			foreach ($elements as $element ) {

				if ($tagname == 'a') {
					$element->setAttribute('href', $val);
				}
				else {
					$element->nodeValue = $val;	
				}
			}
		}

		foreach ($inject['class'] as $class => $val) {
			$finder = new DomXPath($doc);
			$elements = $finder->query( "//*[contains(@class, '" . $class . "')]" );
			
			foreach ($elements as $element ) {

				$element->nodeValue = $val;	
			}
		}

		foreach ($inject['id'] as $id => $val) {
			$element = $doc->getElementById($id);
			
			if ( isset($element) ) {
				
				$element->nodeValue = $val;
			}	
		}

		return $doc->saveHTML();
	}

	public function send() {
		
		$to 	= $this->data['customerEmail'];
		$from 	= 'no-reply@bh.org.il';

		$customer_message = $this->generateMessage(
			'customer_message',
			array(
				'tag' => array(
					'a' => CUSTOM_PAYMENT_FORM_URL . '?key=' . $this->data['paymentKey']
				)
			)
		);

		$issuer_message = $this->generateMessage(
			'issuer_message',
			array(
				'class' => array(
					'payment-key' 		=> $this->data['paymentKey'],
					'transaction-key' 	=> $this->data['transactionKey']
				)
			)
		);
		
		$sentmail_customer = $this->sendMail($this->data['customerEmail'], 'Beit Hatfutsot - Payment Link', $this->data['issuerEmail'], $this->data['issuerName'], $customer_message);
		$sentmail_issuer = $this->sendMail($this->data['issuerEmail'], 'Custom Payment', $from, 'Beit Hatfutsot', $issuer_message);

		if ($sentmail_customer && $sentmail_issuer) {

			return $this->update(
				array(
					'sentTo' => $this->data['customerEmail']
				)
			);
		}
		
		return false;
	}

	public function resend($email) {

		$updated = $this->update(
			array(
				'customerEmail' => $email
			)
		);

		return $this->send();
	}

	public function paid($confirmation) {

		return $this->update(
			array(
				'paid' => 1,
				'confirmation' => $confirmation
			)
		);
	}

	public function delete() {
		global $wpdb;

		if ($this->data['paid']) {
			return false;
		}

		$deleted = $wpdb->delete( $this->dbTableName, array('paymentKey' => $this->data['paymentKey']) );

		if ($deleted) {
			unset( $this );
			return $deleted;
		}

		return false;
	}
}
