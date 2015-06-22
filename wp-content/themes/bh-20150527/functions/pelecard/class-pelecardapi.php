<?php

require_once('class-inputhandler.php');
require_once('class-pelecardrequest.php');
require_once('class-pelecardresponse.php');

class PelecardApi {
    private $url = 'https://ws101.pelecard.biz/webservices.asmx/',
            $terminalLogin = array(),
            $inputHandler,

            $currencyMap = array(
                'nis' => '1',
                'usd' => '2',
                'eur' => '978'
            ),

            $paymentParams = array(
                'shopNo'    => '001',
                'token'     => '',
                'authNum'   => ''
            ),

            $requiredKeysMap = array(

                'construct' => array(
                    'userName'  => 'userName',
                    'password'  => 'password',
                    'termNo'    => 'termNo'
                ),

                'checkCard' => array(
                    'cardNo'    => 'creditCard',
                    'cardDate'  => 'creditCardDateMmyy'
                ),

                'authCard' => array(
                    'cardNo'    => 'creditCard',
                    'cardDate'  => 'creditCardDateMmyy',
                    'total'     => 'total',
                    'currency'  => 'currency',
                    'cvv'       => 'cvv2',
                    'id'        => 'id'
                ),

                'makePayment' => array(
                    'cardNo'    => 'creditCard',
                    'cardDate'  => 'creditCardDateMmyy',
                    'total'     => 'total',
                    'currency'  => 'currency',
                    'cvv'       => 'cvv2',
                    'id'        => 'id'
                )
            );

    public function __construct($terminalLogin) {
        $this->inputHandler = new InputHandler($this->requiredKeysMap);
        if ( $this->inputHandler->checkInput('construct', $terminalLogin) ) {
            $this->terminalLogin = $terminalLogin;
        }
        else {
            throw new Exception('Bad API parameters');
        }
    }

    private function makeRequest($method, $requestData, $transactionKey, $optionalHeaders = null)	{
        $data = array_merge($this->terminalLogin, $requestData);

        $request = new PelecardRequest($this->url, $method, $data, $transactionKey, $optionalHeaders);

        return $request->send();
    }

    private function getCurrencyCode($currencyName) {
        $map = $this->currencyMap;

        return isset($map[strtolower($currencyName)]) ? $map[strtolower($currencyName)] : false;
    }

    private function getTotal($total) {
        
        return $total * 100;
    }

    private function processRequest($method, $data, $transactionKey) {

        if ( strlen($transactionKey) < 5 ) {
            
            if ( $this->inputHandler->checkInput($method, $data) ) {

                $requestData = $this->inputHandler->processInput($method, $data);
     
                return $this->makeRequest($method, $requestData, $transactionKey);
            }
            else {
                throw new Exception('Incomplete request data');
            }   
        }
        else {
            throw new Exception('transaction key must be shorter than 5 characters');
        }
    }

    private function processPaymentData($paymentData) {
        if ( isset($paymentData['total']) ) $paymentData['total'] = $this->getTotal($paymentData['total']);
        if ( isset($paymentData['currency']) ) $paymentData['currency'] = $this->getCurrencyCode($paymentData['currency']);

        return array_merge($paymentData, $this->paymentParams);
    }

    public function checkCard($cardData) {
        return $this->processRequest('checkCard', $cardData);
    }

    public function authCard($paymentData, $transactionKey) {
        $paymentData = $this->processPaymentData($paymentData);

        return $this->processRequest('authCard', $paymentData, $transactionKey);
    }

    public function makePayment($paymentData, $transactionKey) {
        $paymentData = $this->processPaymentData($paymentData);

        return $this->processRequest('makePayment', $paymentData, $transactionKey);
    }

    public function getCurrencyName($currencyCode) {
        $map = array_flip($this->currencyMap);

        return isset($map[strtolower($currencyCode)]) ? $map[strtolower($currencyCode)] : false;
    }
}
