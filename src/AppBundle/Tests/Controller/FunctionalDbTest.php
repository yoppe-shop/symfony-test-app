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
use AppBundle\Controller\DbTestsController;

class FunctionalDbTest extends WebTestCase
{
    public function testGetProducts()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/db_tests/products');

        // HIER DIE TESTS DURCHFÜHREN

        // echo $client->getResponse()->getContent();
    }
}
