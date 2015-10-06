<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HighSpeedFuelControllerTest extends WebTestCase
{
    public function testShowIndex(){

        $client = static::createClient();
        $crawler = $client->request('GET', '/hifuel');

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("High Speed Fuel list")')->count()
        );
    }
}
