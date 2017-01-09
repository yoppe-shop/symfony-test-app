<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FunctionalTest extends WebTestCase
{
    protected function setUp()
    {
        // Falls vorhanden, wird diese Methode anfangs aufgerufen zum Initialisieren der Testumgebung
    }

    public function testGetValueAction()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/testing/get_value');

        $this->assertGreaterThan(
            999,
            $client->getResponse()->getContent()
        );
    }

    public function testGetHtml()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/testing/get_html');

        $this->assertCount(3, $crawler->filter('div'));
    }
}
