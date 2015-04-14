<?php

namespace AppBundle\Utils;

use Symfony\Component\DomCrawler\Crawler;

class GeoUtils
{
    public function fetchDataID($flightNo)
    {
        $flightIDS = [];
        
        $flightPage = file_get_contents('http://www.flightradar24.com/data/flights/'.$flightNo.'/');
        $crawler = new Crawler($flightPage);
        $crawler = $crawler->filter('button.doPlayback');

        foreach($crawler as $trInfo) {
            $flightIDS[] = $trInfo->getAttribute('data-flight');
        }

        return $flightIDS;
    }
}