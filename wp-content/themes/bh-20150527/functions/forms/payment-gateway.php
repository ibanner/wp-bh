<?php

require_once(__DIR__.'/../pelecard/class-pelecardapi.php');

$terminalLogin = array(
    'userName'          => 'beittfozot', //'PeleTest3',
    'password'          => 'goc+g)Jf', //'Pelecard@Test',
    'termNo'            => '5784084' //'0962210'
);

$pelecardApi = new PelecardApi($terminalLogin);

$pelecardRequest = null;

$paymentData = array(
    'cardNo'    => isset($_POST['cardno']) ? $_POST['cardno'] : '',
    'cardDate'  => isset($_POST['cardmonth']) && isset($_POST['cardyear']) ? $_POST['cardmonth'] . $_POST['cardyear'] : '',
    'currency'  => 'nis',
    'cvv'       => isset($_POST['cardcvv']) ? $_POST['cardcvv'] : '',
    'id'        => isset($_POST['cardid']) ? $_POST['cardid'] : ''
);

function get_reason($transaction=false) {
    
    if ( !$transaction || $transaction->getResponse()->getCode() != 1 ) {
        return get_exception_message();
    }

    $problematic_card_message = __('There was a problem with the credit card information. Please make sure credit card info is correct.', 'BH');
    
    return $problematic_card_message;
    
}

function get_exception_message() {
    return __('We have expirienced a technical problem. Please try again later.', 'BH');
}

function get_total_error() {
    return __('There was a problem with the payment total amount. Please contact us.', 'BH');
}

function get_transactionkey_error() {
    return __('There was a problem with the customer ID. Please contact us.', 'BH');
}
