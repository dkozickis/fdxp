<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testWithoutIndexAuth()
    {
        $client = static::createClient();
        $client->followRedirects();
        $crawler = $client->request('GET', '/');

        //$this->assertRegExp('/\/login$/', $client->getRequest()->getUri());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Login")')->count());
    }

    public function testWithIndexAuth()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'admin',
        ));
        $client->followRedirects();
        $crawler = $client->request('GET', '/');

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Logout")')->count());
    }

}
