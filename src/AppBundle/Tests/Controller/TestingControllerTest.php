<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TestingControllerTest extends WebTestCase
{
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
