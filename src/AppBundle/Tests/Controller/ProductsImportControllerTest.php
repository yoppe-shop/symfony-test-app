<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductsImportControllerTest extends WebTestCase
{
    public function testCsv()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/csv');
    }

}
