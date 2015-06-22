<?php

class PelecardRequest {
    private $url, $operation, $data, $transactionKey, $optionalHeaders, $response,

            $operationMap = array(
                'makePayment'   => 'DebitRegularType',
                'authCard'      => 'AuthrizeCreditCard',
                'checkCard'     => 'CheckCreditCard'
            );

    public function __construct($url, $method, $data, $transactionKey, $optionalHeaders) {
        $this->url = $url;
        $this->operation = $this->getOperation($method);
        $this->data = $data;
        $this->transactionKey = $transactionKey;
        $this->optionalHeaders = $optionalHeaders;
    }

    private function getOperation($method) {
        if ( array_key_exists($method, $this->operationMap) ) {
            return $this->operationMap[$method];
        }
        else {
            return null;
        }
    }

    private function getMethod($operation) {
        if ( array_search($operation, $this->operationMap) !== false ) {
            return array_flip($this->operationMap)[$operation];
        }
        else {
            return null;
        }
    }

    private function generateTransactionID() {
        $cardNo = $this->data['creditCard'];
        $cardFourDigits = substr($cardNo, strlen($cardNo)-4, 4);
        $datetime = date_i18n('d') . date_i18n('m') . date_i18n('y') . date_i18n('H') . date_i18n('i');
 
        return $this->transactionKey . $datetime . '-' . $cardFourDigits;
    }

    private function buildResponse($rawResponse) {
        $responseArr = array(substr(trim(strip_tags($rawResponse)),0,3), trim(strip_tags($rawResponse)));
        $pelecardCode = $responseArr[0];
        $responseStr = $responseArr[1];

        if ( $this->getMethod($this->operation) === 'makePayment' ) {
            $confirm = true;
        }
        else {
            $confirm = false;
        }

        return new PelecardResponse($pelecardCode, $responseStr, $confirm);
    }

    public function getResponse() {
        return $this->response;
    }

    public function send() {
        $this->data['parmx'] = $this->generateTransactionID();

        $params = array('http' => array(
            'method' => 'POST',
            'content' => http_build_query($this->data)
        ));

        $requestUrl = $this->url . $this->operation;

        if ($this->optionalHeaders !== null) {
            $params['http']['header'] = $this->optionalHeaders;
        }
        $ctx = stream_context_create($params);
        $fp = @fopen($requestUrl, 'rb', false, $ctx);
        if (!$fp) {
            throw new Exception($php_errormsg);
        }
        $rawResponse = @stream_get_contents($fp);
        if ($rawResponse === false) {
            throw new Exception('Problem reading data from ' . $requestUrl);
        }
        else {
            $this->response = $this->buildResponse($rawResponse);
        }

        return $this;
    }
}
