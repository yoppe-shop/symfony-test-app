<?php

namespace ShopBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductsImportControllerTest extends WebTestCase
{
    protected $em;
    const DIR_SEP = "\\";

    protected function setUp()
    {
        self::bootKernel();

        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager('mysql');
    }

    public function testCsvImportAction()
    {
        $csv = file_get_contents(static::$kernel->getRootDir() . self::DIR_SEP . ".." . self::DIR_SEP . "src" . self::DIR_SEP . "ShopBundle" . self::DIR_SEP . "Resources" . self::DIR_SEP . "testFiles" . self::DIR_SEP . "product_test.csv");
        echo $csv; exit;
    }
}
