<?php

class InputHandler {
    private $requiredKeysMap;

    public function __construct($requiredKeysMap) {
        $this->requiredKeysMap = $requiredKeysMap;
    }

    public function checkInput($method, $input) {
        if ( array_key_exists($method, $this->requiredKeysMap) ) {
            $requiredKeys = $this->requiredKeysMap[$method];
            if ( count(array_intersect_key($requiredKeys, $input)) === count($requiredKeys) ) {
                return true;
            }
            else {
                return false;
            }
        }
        else {
            throw new Exception('InputHandler: Unknown method: ' . $method);
        }
    }

    public function processInput($method, $input) {
        if ( array_key_exists($method, $this->requiredKeysMap) ) {
            $requiredKeys = $this->requiredKeysMap[$method];
            $requestData = array();

            foreach($input as $inputKey => $val) {
                $requiredKey = array_key_exists($inputKey, $requiredKeys);

                if ($requiredKey !== false) {
                    $requestData[$requiredKeys[$inputKey]] = $val;
                }
                else {
                    $requestData[$inputKey] = $val;
                }
            }

            return $requestData;
        }
        else {
            throw new Exception('InputHandler: Unknown method: ' . $method);
        }
    }
}
