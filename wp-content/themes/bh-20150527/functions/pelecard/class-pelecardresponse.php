<?php

class PelecardResponse {
    private $code, $responseStr, $pelecardCode, $successful, $failReason, 
            $creditCompany, $confirmed=false, $confirmNo, $id, $total, $currencyCode,

            $creditCompanyMap = array(
                '1' => 'Isracard',
                '2' => 'Visa C.A.L',
                '3' => 'Diners',
                '4' => 'Amex',
                '5' => 'JCB',
                '6' => 'Leumicard'
            );

    public function __construct($pelecardCode, $responseStr, $confirm) {
        $this->responseStr = $responseStr;
        $this->pelecardCode = $pelecardCode;
        $this->setID();
        $this->setCreditCompany();
        $this->setTotal();
        $this->setCurrencyCode();
        
        if (strlen($responseStr) >= 3 && $pelecardCode !== '000') {
            $this->makeError();
        }
        elseif ($pelecardCode === '000' || strlen($pelecardCode) === 1) {
            $this->makeSuccessful($confirm);
        }
    }

    private function setID() {
        $length = strlen($responseStr);
        $start = $length - 19;
        
        $this->id = substr($this->responseStr, $start, 19);
    }

    private function setCreditCompany() {

        $companyCode = substr($this->responseStr, 59, 1);
        
        if ( array_key_exists($companyCode, $this->creditCompanyMap) ) {
            $this->creditCompany =  $this->creditCompanyMap[$companyCode];
        }
    }

    private function setTotal() {
        $this->total = intval( substr($this->responseStr, 35, 8) ) / 100;
    }

    private function setCurrencyCode() {
        $this->currencyCode = substr($this->responseStr, 64, 1);
    }

    private function setConfirmation() {
        
        $responseStr = $this->responseStr;
        $confirmationAuthority = substr($responseStr, 69, 1);
        if ($this->isSuccessful() && $confirmationAuthority !== '0') {
            $this->confirmed = true;
            $this->confirmNo = substr($responseStr, 70, 7);
        }
    }

    private function makeError() {
        $this->successful = false;

        $possibleInputErrors = array('004', '017', '033', '036', '039', '057', '058', '059');

        if ( false !== array_search($this->pelecardCode, $possibleInputErrors) ) {
            $this->code = 1;
            $this->failReason = 'Bad input';
        }
        else {
            $this->code = 0;
            $this->failReason = 'General error';
        }
    }

    private function makeSuccessful($confirm) {
        
        $this->successful = true;

        if ($confirm !== false) {
            $this->setConfirmation();
        }
    }

    public function getID() {
        return $this->id;
    }

    public function isConfirmed() {
        return $this->$confirmed;
    }

    public function getConfirmNo() {
        return $this->confirmNo;
    }

    public function isSuccessful() {
        return $this->successful;
    }

    public function getCode() {
        return $this->code;
    }

    public function getReason() {
        return $this->failReason;
    }

    public function getCreditCompany() {
        return $this->creditCompany;
    }

    public function getTotal() {
        return $this->total;
    }

    public function getCurrencyCode() {
        return $this->currencyCode;
    }    
}
