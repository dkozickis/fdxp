<?php

namespace AppBundle\Utils;

use Symfony\Component\DomCrawler\Crawler;

class WXUtils{

    public function getMetars($airports){

        $airportString = $this->generateAirportString($airports);

        $metarXML = file_get_contents('http://aviationweather.gov/adds/dataserver_current/httpparam?'.
            'datasource=metars&requestType=retrieve&format=xml&mostRecentForEachStation=constraint&'.
            'hoursBeforeNow=6&stationString='.urlencode($airportString));

        $crawler = new Crawler($metarXML);
        $metars = $crawler->filter('raw_text')->extract(array(
            '_text'
        ));

        foreach($metars as $key => $value){
            preg_match('/[A-Z]{4}/', $value, $airportArray);
            $metars[$airportArray[0]] = $metars[$key];
            unset($metars[$key]);
        }

        $sortedMetars = array_merge(array_flip($airports), $metars);

        return $sortedMetars;

    }

    public function getTafs($airports){

        $airportString = $this->generateAirportString($airports);

        $tafXML = file_get_contents('http://aviationweather.gov/adds/dataserver_current/httpparam?'.
            'datasource=tafs&requestType=retrieve&format=xml&mostRecentForEachStation=postfilter&'.
            '&startTime='.(time() - 21600).'&endTime='.time().'&stationString='.urlencode($airportString));

        $crawler = new Crawler($tafXML);
        $tafs = $crawler->filter('raw_text')->extract(array(
            '_text'
        ));

        foreach($tafs as $key => $value){
            preg_match('/[A-Z]{4}/', $value, $airportArray);
            $tafs[$airportArray[0]] = $tafs[$key];
            $tafs[$airportArray[0]] = preg_replace('/(BECMG)|((PROB30|PROB40)(\sTEMPO)?)|(TEMPO)|(FM)/','<br/>$0', $value);
            unset($tafs[$key]);
        }
        
        $sortedTafs = array_merge(array_flip($airports), $tafs);

        return $sortedTafs;
    }

    public function generateAirportString($airports){

        $airportString = '';
        foreach ($airports as $airport) {
            $airportString .= $airport." ";
        }

        return $airportString;
    }

}
