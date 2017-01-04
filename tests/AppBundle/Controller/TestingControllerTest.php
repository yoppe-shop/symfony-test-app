<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Controller\TestingController;

class TestingControllerTest extends \PHPUnit_Framework_TestCase
{
    public function testGetNumber()
    {
        $testing = new TestingController();
        $result = $testing->getNumber();

        $this->assertEquals(1000, $result);
    }

    /**
     * @depends testGetNumber
     */
    public function testGetValue()
    {
        $testing = new TestingController();
        $result = $testing->getValueAction();

        $this->assertEquals(1000, $result->getContent());
        // $this->assertTrue($result->getContent() == 999);
    }
}
