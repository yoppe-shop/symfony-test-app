<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Controller\DbTestsController;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Product;
use AppBundle\Entity\ProductAttribute;

class MockTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        // Falls vorhanden, wird diese Methode anfangs aufgerufen zum Initialisieren der Testumgebung
    }

    public function testGetProducts()
    {
        $product = $this->createMock(Product::class);

        // WENN ICH WEISS, DIE METHODE WIRD 1x AUFGERUFEN:
        // $product->expects($this->once())
        // IN ALLEN ANDEREN FÄLLEN:
        // $product->expects($this->any())

        $product->expects($this->any())
            ->method('getId')
            ->will($this->returnValue(1));
        $product->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('Salami Pizza'));
        $product->expects($this->any())
            ->method('getModel')
            ->will($this->returnValue('40404040'));
        $product->expects($this->any())
            ->method('getCreated')
            ->will($this->returnValue(new \DateTime('now')));
        $product->expects($this->any())
            ->method('getProductAttributes')
            ->will($this->returnValue(array()));

        $product2 = $this->createMock(Product::class);
        $product2->expects($this->any())
            ->method('getId')
            ->will($this->returnValue(2));
        $product2->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('Thunfisch Pizza'));
        $product2->expects($this->any())
            ->method('getModel')
            ->will($this->returnValue('50505050'));
        $product2->expects($this->any())
            ->method('getCreated')
            ->will($this->returnValue(new \DateTime('now')));
        $product2->expects($this->any())
            ->method('getProductAttributes')
            ->will($this->returnValue(array()));

        $productRepository = $this
            ->getMockBuilder(EntityRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $productRepository->expects($this->any())
            ->method('find')
            ->will($this->returnValue($product));
       $productRepository->expects($this->any())
            ->method('findAll')
            ->will($this->returnValue([$product, $product2]));

        // Last, mock the EntityManager to return the mock of the repository
        $em = $this
            ->getMockBuilder(ObjectManager::class)
            ->disableOriginalConstructor()
            ->getMock();
        $em->expects($this->any())
            ->method('getRepository')
            ->will($this->returnValue($productRepository));

        $dbTest = new DbTestsController();
        $result = $dbTest->getProducts($em);

        $this->assertEquals(2, count($result));
    }
}
