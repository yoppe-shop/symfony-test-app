<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Controller\TestingController;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Product;
use AppBundle\Entity\ProductAttribute;

class TestingControllerTest extends \PHPUnit_Framework_TestCase
{
    public function testGetProducts()
    {
        $product = $this->createMock(Product::class);

        $testing = new TestingController();
        $result = $testing->getProducts();
        $this->assertEquals(3, count($result));
    }
/*
    public function testGetNumber()
    {
        $testing = new TestingController();
        $result = $testing->getNumber();

        $this->assertEquals(1000, $result);
    }
*/
    /**
     * @depends testGetNumber
     */
/*
    public function testGetValue()
    {
        $testing = new TestingController();
        $result = $testing->getValueAction();

        $this->assertEquals(1000, $result->getContent());
        // $this->assertTrue($result->getContent() == 999);
    }
*/
}
