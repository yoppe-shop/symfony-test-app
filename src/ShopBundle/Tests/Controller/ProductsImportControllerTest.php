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

    protected function loadCsv()
    {
        $this->csv = file_get_contents(static::$kernel->getRootDir() . DIR_SEP . ".." . DIR_SEP . "src" . DIR_SEP . "ShopBundle" . DIR_SEP . "Resources" . DIR_SEP . "testFiles" . DIR_SEP . "product_test.csv");
    }

    public function testControllerFunctions()
    {
        /**
        * MAIN TEST
        * Tests the main functions of the controller:
        */
        $this->loadCsv();

        $this->initializeCsvFile();
        $lines = $this->getLines($this->csv);
        $titleOfIndexes = $this->titleOfIndexes($lines[0]);
        unset($lines[0]);
        foreach ($lines as $line) {
            $this->createDataArray($line, $titleOfIndexes);
        }
    }

    public function testEntities()
    {
        /**
        *  Tests only that there are correct products and products options / values in the database:
        */
        $products = $this->em 
            ->createQuery('
                SELECT p, po, pov 
                FROM ShopBundle:Product p 
                LEFT JOIN p.productsOptions po
                LEFT JOIN p.productsOptionsValues pov
                WHERE p.productsModel = \'999999\'
            ')
            ->getResult();

        $this->assertGreaterThanOrEqual(2, count($products[0]->getProductsOptions()));
        $this->assertGreaterThanOrEqual(4, count($products[0]->getProductsOptionsValues()));

        $poRequired = array_flip(['Farbe', 'Gewicht']);
        foreach ($products[0]->getProductsOptions() as $po) {
            if (isset($poRequired[$po->getProductsOptionsName()]))
            {
                unset($poRequired[$po->getProductsOptionsName()]);
            }
        }
        $this->assertEmpty($poRequired);

        $povRequired = array_flip(['rot', 'red', '10 kg']);
        foreach ($products[0]->getProductsOptionsValues() as $pov) {
            if (isset($povRequired[$pov->getProductsOptionsValuesName()]))
            {
                unset($povRequired[$pov->getProductsOptionsValuesName()]);
            }
        }
        $this->assertEmpty($povRequired);
    }

    public function testProductsOptions()
    {
        $method = self::getMethod('ShopBundle\\Controller\\ProductsImportController', 'productsOptions');
        $productsOptions = $method->invokeArgs($this->productsImportController, [$this->em]);
        $this->assertGreaterThanOrEqual(8, count($productsOptions));
    }

    public function initializeCsvFile()
    {
        $method = self::getMethod('ShopBundle\\Controller\\ProductsImportController', 'initializeCsvFile');
        $method->invokeArgs($this->productsImportController, [&$this->csv]);
        $this->assertFalse(strpos($this->csv, "\r"));
    }

    public function getLines($csv)
    {
        $method = self::getMethod('ShopBundle\\Controller\\ProductsImportController', 'getLines');
        $lines = $method->invokeArgs($this->productsImportController, [&$this->csv]);
        $this->assertCount(2, $lines);
        return $lines;        
    }

    public function titleOfIndexes($line0)
    {
        $method = self::getMethod('ShopBundle\\Controller\\ProductsImportController', 'titleOfIndexes');
        $titleOfIndexes = $method->invokeArgs($this->productsImportController, [$line0]);
        $this->assertCount(20, $titleOfIndexes);
        return $titleOfIndexes;
    }

    public function createDataArray($line, $titleOfIndexes)
    {
        $method = self::getMethod('ShopBundle\\Controller\\ProductsImportController', 'createDataArray');
        $data = $method->invokeArgs($this->productsImportController, [$this->em, &$line, $titleOfIndexes]);
        $this->assertArrayHasKey('attributes', $data);
        $this->assertArrayHasKey('product', $data);
        $this->assertArrayHasKey('productsDescription', $data);
        $this->assertArrayHasKey('model', $data['product']);
        if (isset($data['product']['model']) && $data['product']['model'] == '99999')
        {
            $this->assertCount(4, $data['product']);
            $this->assertCount(3, $data['productsDescription']);
            $this->assertCount(8, $data['attributes']);
            $this->assertEquals($data['product'], [
                    'model' => '99999',
                    'ean' => '43123456789',
                    'price' => '19,99',
                    'status' => '1',
            ]);
            $this->assertEquals($data['productsDescription'], [
                    'name' => [
                        'de' => 'Bürotisch'
                    ],
                    'description' => [
                        'de' => 'Dieser Bürotisch bietet Ihnen viel Platz für alle Ihre Büroutensilien.'
                    ],
                    'short_description' => [
                        'de' => 'Bürotisch mit viel Platz'
                    ]
            ]);
            $this->assertEquals($data['attributes']['farbe'],  [
                'de' => [
                    'action' => '',
                    'value' => 'rot',
                ],
                'en' => [
                    'action' => '',
                    'value' => 'red',
                ],
            ]);
        }
        return $data;        
    }

    public function registerDataProvider() {
        return array("csv", "");
    }

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
