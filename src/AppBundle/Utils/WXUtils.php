<?php

namespace AppBundle\Utils;

use Symfony\Component\DomCrawler\Crawler;

class WXUtils{

    public function getMetars($airportString){

        $metarXML = file_get_contents('http://weather.aero/dataserver_current/httpparam?'.
            'datasource=metars&requestType=retrieve&format=xml&mostRecentForEachStation=constraint&'.
            'hoursBeforeNow=6&stationString='.urlencode($airportString));

        $crawler = new Crawler($metarXML);
        $metars = $crawler->filter('raw_text')->extract(array(
            '_text'
        ));

        return $metars;

    }

    public function getTafs($airportString){

        $tafXML = file_get_contents('http://weather.aero/dataserver_current/httpparam?'.
            'datasource=tafs&requestType=retrieve&format=xml&mostRecentForEachStation=postfilter&'.
            '&startTime='.(time() - 21600).'&endTime='.time().'&stationString='.urlencode($airportString));

        $crawler = new Crawler($tafXML);
        $tafs = $crawler->filter('raw_text')->extract(array(
            '_text'
        ));

        return $tafs;
    }

}