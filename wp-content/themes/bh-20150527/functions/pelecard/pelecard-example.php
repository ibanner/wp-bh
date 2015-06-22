<?php
/*
 * This is an example of how to use the Pelecard API
 */

require_once('./../../../../../wp-load.php');
require_once('class-pelecardapi.php');

$terminalLogin = array(
    'userName'          => /*'beittfozot',*/ 'PeleTest',
    'password'          => /*'goc+g)Jf',*/ 'Pelecard@2013',
    'termNo'            => /*'5784084'*/ '0962210'
);

$paymentData = array(
    'cardNo' => '37551029000620',
    'cardDate' => '1215',
    'total' => '10',
    'currency' => 'nis',
    'cvv' => '143',
    'id' => '890109629'
);

$transactionKey = 'TEST';

$cardData = array(
    'cardNo' => '458045804580',
    'cardDate' => '0113'
);


$pelecardapi = new PelecardApi($terminalLogin);

try {
    $result = $pelecardapi->authCard($paymentData, $transactionKey);
    $b=2;
}
catch(Exception $e) {
    echo $e.getMessage();
}

