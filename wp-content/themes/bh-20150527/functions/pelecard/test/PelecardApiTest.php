<?php
/**
 * Created by PhpStorm.
 * User: danny
 * Date: 5/25/14
 * Time: 12:54 PM
 */

namespace test;

require_once('../class-pelecardapi.php');

class PelecardApiTest extends \PHPUnit_Framework_TestCase {
    private $mock,

            $apiParams = array(
                'userName'          => 'PeleTest',
                'password'          => 'Pelecard@2013',
                'termNo'            => '0962210',
                'transactionKey'    => 'test'
            ),

            $testData = array(
                'cardNo'    => '5326100351583426',
                'cardDate'  => '0215',
                'total'     => '5',
                'currency'  => 'nis',
                'cvv'       => '223',
                'id'        => '060606060'
            );

    public function setUp() {
        $this->mock = $this->getMockBuilder('\PelecardApi')
            ->setConstructorArgs(array($this->apiParams))
            ->setMethods(null)
            ->getMock();
    }

    public function test() {
        $result = $this->mock->makePayment($this->testData);
        var_dump($result);
    }
}

 