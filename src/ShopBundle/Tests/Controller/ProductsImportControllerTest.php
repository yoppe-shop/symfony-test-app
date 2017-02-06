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
        
        $this->productsImportController = new ProductsImportController();
    }

    protected function loadCsv()
    {
        $this->csv = file_get_contents(static::$kernel->getRootDir() . DIR_SEP . ".." . DIR_SEP . "src" . DIR_SEP . "ShopBundle" . DIR_SEP . "Resources" . DIR_SEP . "testFiles" . DIR_SEP . "product_test.csv");
    }

    public function testControllerFunctions()
    {
        $this->loadCsv();

        $this->initializeCsvFile();
        $lines = $this->getLines($this->csv);
        $titleOfIndexes = $this->titleOfIndexes($lines[0]);
        unset($lines[0]);
        foreach ($lines as $line) {
            $this->createDataArray($line, $titleOfIndexes);
        }
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
print_r($data);
        $this->assertArrayHasKey('attributes', $data);
        $this->assertArrayHasKey('product', $data);
        $this->assertArrayHasKey('productDescription', $data);
        $this->assertArrayHasKey('model', $data['product']);
        if (isset($data['product']['model']) && $data['product']['model'] == '99999')
        {
            $this->assertCount(8, $data['product']);
            $this->assertCount(3, $data['productDescription']);
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
}
