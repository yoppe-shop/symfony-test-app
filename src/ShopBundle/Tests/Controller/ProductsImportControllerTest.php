<?php

namespace ShopBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use ShopBundle\Controller\ProductsImportController;

class ProductsImportControllerTest extends WebTestCase
{
    protected $em;
    protected $csv = '';
    protected $productsImportController;

    protected function setUp()
    {
        self::bootKernel();

        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager('mysql');

        $this->debug = static::$kernel->getContainer()->get('debug');

        $this->productsImportController = new ProductsImportController();
    }

    protected function loadCsv($file)
    {
        $this->csv = file_get_contents($file);
    }

    /**
    * @dataProvider csvDataProvider
    */
    public function testProductsOptions($file)
    {
        echo $file;
        return true;
        $method = self::getMethod('ShopBundle\\Controller\\ProductsImportController', 'productsOptions');
        $productsOptions = $method->invokeArgs($this->productsImportController, [$this->em]);
        $this->assertGreaterThanOrEqual(8, count($productsOptions));
    }

    public function csvDataProvider() {
        $fileDir = static::$kernel->getRootDir() . DIR_SEP . ".." . DIR_SEP . "src" . DIR_SEP . "ShopBundle" . DIR_SEP . "Resources" . DIR_SEP . "testFiles" . DIR_SEP;
        return [
            $fileDir . "product_test1.csv",
            $fileDir . "product_test2.csv",
            $fileDir . "product_test3.csv",
        ];
    }

/**
* $method = self::getMethod('ShopBundle\\Controller\\ProductsImportController', 'productsOptions');
* $productsOptions = $method->invokeArgs($this->productsImportController, [$this->em]);
* $this->assertGreaterThanOrEqual(8, count($productsOptions));
*/

    protected static function getMethod($className, $methodName)
    {
        $class = new \ReflectionClass($className);
        $method = $class->getMethod($methodName);
        $method->setAccessible(true);
        return $method;
    }

    protected static function getPropertyValue($object, $propertyName)
    {
        $reflectionClass = new ReflectionClass(get_class($object));
        $reflectionProperty = $reflectionClass->getProperty($propertyName);
        $reflectionProperty->setAccessible(true);

        return $reflectionProperty->getValue($object);
    }
}
