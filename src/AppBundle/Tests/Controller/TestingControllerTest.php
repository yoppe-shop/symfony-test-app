<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Controller\TestingController;

class TestingControllerTest extends \PHPUnit_Framework_TestCase
{
    public function testGetProducts()
    {
        $testing = new TestingController();
        $result = $testing->getProducts();
        $this->assertEquals(3, count($result));
    }

    public function testGetNumber()
    {
        $testing = new TestingController();
        $result = $testing->getNumber();

        $this->assertEquals(2000, $result);
    }

    /**
     * @depends testGetNumber
     */
    public function testGetValue()
    {
        $testing = new TestingController();
        $result = $testing->getValueAction();

        $this->assertEquals("Ausgabe von \$i: 1002", $result->getContent());
        // $this->assertTrue($result->getContent() == 999);
    }
}
