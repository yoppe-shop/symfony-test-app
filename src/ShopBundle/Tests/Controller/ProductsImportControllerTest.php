<?php

namespace ShopBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use ShopBundle\Controller\ProductsImportController;

class ProductsImportControllerTest extends WebTestCase
{
    protected $em;
    protected $csv;

    const DIR_SEP = "\\";

    protected function setUp()
    {
        self::bootKernel();

        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager('mysql');
    }

    public function registerDataProvider() {
        return array("csv", "");
    }

    public function testCsvImportAction()
    {
        $this->csv = "Hallo, wie gehts im Büro???"; // file_get_contents(static::$kernel->getRootDir() . self::DIR_SEP . ".." . self::DIR_SEP . "src" . self::DIR_SEP . "ShopBundle" . self::DIR_SEP . "Resources" . self::DIR_SEP . "testFiles" . self::DIR_SEP . "product_test.csv");
    }

    /**
     * @dataProvider testCsvImportAction
     */
    public function testGetCsvProductData()
    {
        // $this->testCsvImportAction();
        $initializeCsvFile = self::getMethod('ShopBundle\\Controller\\ProductsImportController', 'initializeCsvFile');
        $productsImport = new ProductsImportController();
        $initializeCsvFile->invokeArgs($productsImport, [&$this->csv]);
        echo $this->csv;
    }

    protected static function getMethod($className, $methodName)
    {
        $class = new \ReflectionClass($className);
        $method = $class->getMethod($methodName);
        $method->setAccessible(true);
        return $method;
    }
}
