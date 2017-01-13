<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use AppBundle\Controller\FormsController;

class FormsControllerTest extends WebTestCase
{
    protected $em;

    protected function setUp()
    {
        self::bootKernel();

        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testForm()
    {
        echo "In der TestForm-Funktion!\n";
        $client = static::createClient();
        $crawler = $client->request('GET', '/forms/add_product');

        $this->assertGreaterThan(
            0,
            $crawler->filter('form')->count()
        );
        $this->assertGreaterThan(
            2,
            $crawler->filter('input')->count()
        );
        $this->assertEquals(
            1,
            $crawler->filter('form button')->count()
        );
    }

    public function testGetProducts()
    {
        $test = new FormsController();
        $products = $test->getProducts($this->em);
        $this->assertGreaterThan(
            2,
            count($products)
        );
    }

    /**
    * @depends testForm
    */
    public function testResult()
    {
        echo "In der TestResult-Funktion!\n";
        $products = $this->em 
            ->getRepository('AppBundle:Product')
            ->findAll()
        ;
        echo "Anzahl Datensaetze: " . count($products) . "\n";
    }
}
