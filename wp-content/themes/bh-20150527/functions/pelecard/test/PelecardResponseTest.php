<?php
/**
 * Created by PhpStorm.
 * User: danny
 * Date: 5/25/14
 * Time: 3:23 PM
 */

namespace test;

require_once('../class-pelecardresponse.php');

class PelecardResponseTest extends \PHPUnit_Framework_TestCase {

    public function test_should_not_confirm_unsuccessful() {
        $params = array('036', '', 'test', true);
        $params[1] = str_repeat('0', 100);
        $params[1][69] = '2'; // set confirmation authority
        $params[1][59] = '2'; // set credit company

        $responseMock = $this->getMockBuilder('\PelecardResponse')
            ->setConstructorArgs($params)
            ->setMethods(null)
            ->getMock();

        $confirmNo = $responseMock->getConfirmNo();

        $this->assertNull($confirmNo);
    }
}

 