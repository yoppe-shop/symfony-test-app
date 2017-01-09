<?php
/*
Die Datei ins Framework einfügen:

# app/config/config_test.yml
doctrine:
    # ...
    dbal:
        host:     localhost
        dbname:   testdb
        user:     testdb
        password: testdb
*/
namespace Tests\AppBundle\Controller;

use AppBundle\Controller\TestingController;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FunctionalDbTest extends WebTestCase
{
    protected function setUp()
    {
        // Falls vorhanden, wird diese Methode anfangs aufgerufen zum Initialisieren der Testumgebung
    }

    public function testGetProducts()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/testing/products');

        // HIER DIE TESTS DURCHFÜHREN

        // echo $client->getResponse()->getContent();
    }
}
