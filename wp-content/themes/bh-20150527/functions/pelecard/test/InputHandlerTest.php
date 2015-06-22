<?php
/**
 * Created by PhpStorm.
 * User: danny
 * Date: 5/25/14
 * Time: 12:56 PM
 */

namespace test;

require_once('../class-inputhandler.php');

class InputHandlerTest extends \PHPUnit_Framework_TestCase {
    private $mock,

            $requiredKeysMap = array(

                'construct' => array(
                    'userName'          => 'userName',
                    'password'          => 'password',
                    'termNo'            => 'termNo',
                    'transactionKey'    => 'transactionKey'
                ),

                'checkCard' => array(
                    'cardNo'    => 'creditCard',
                    'cardDate'  => 'creditCardDateMmyy'
                ),

                'makePayment' => array(
                    'cardNo'    => 'creditCard',
                    'cardDate'  => 'creditCardDateMmyy',
                    'total'     => 'total',
                    'currency'  => 'currency',
                    'cvv'       => 'cvv2',
                    'id'        => 'id'
                )
            ),

            $testInput = array(
                'cardNo'    => 'test',
                'cardDate'  => 'test',
                'total'     => 'test',
                'currency'  => 'test',
                'cvv'       => 'test',
                'id'        => 'test'
            );

    public function setUp() {
        $this->mock = $this->getMockBuilder('\InputHandler')
            ->setConstructorArgs( array($this->requiredKeysMap) )
            ->setMethods(null)
            ->getMock();
    }

    public function test_check() {
        $badInput = array(
            'not' => 'good'
        );

        $good = $this->mock->checkInput('makePayment', $this->testInput);
        $bad = $this->mock->checkInput('makePayment', $badInput);

        $this->assertTrue($good);
        $this->assertFalse($bad);
    }

    public function test_process() {
        $requestData = $this->mock->processInput('makePayment', $this->testInput);

        $diff = array_diff_key($requestData, array_flip($this->requiredKeysMap['makePayment']));

        $this->assertEquals(0, count($diff));
    }
}

 