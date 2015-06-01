<?php

namespace AppBundle\Utils;

use Symfony\Component\DomCrawler\Crawler;

class GeoUtils
{
    /**
     * @param string $flightNo
     */
    public function fetchDataID($flightNo)
    {
        $flightIDS = [];

        $flightPage = file_get_contents('http://www.flightradar24.com/data/flights/'.$flightNo.'/');
        $crawler = new Crawler($flightPage);
        $crawler = $crawler->filter('button.doPlayback');

        foreach ($crawler as $trInfo) {
            $flightIDS[] = $trInfo->getAttribute('data-flight');
        }

        return $flightIDS;
    }

    public function convertDMStoDD($dms)
    {
        preg_match_all('~([0-9]{6,7})([N|S|W|E]{1})~', $dms, $dmsMatch, PREG_SET_ORDER);
        $dmsArray = [];

        switch (strlen($dmsMatch[0][1])) {
            case 6:
                preg_match('~(?<deg>[0-9]{2})(?<min>[0-9]{2})(?<sec>[0-9]{2})~', $dmsMatch[0][0], $dmsArray);
                break;
            case 7:
                preg_match('~(?<deg>[0-9]{3})(?<min>[0-9]{2})(?<sec>[0-9]{2})~', $dmsMatch[0][0], $dmsArray);
                break;
        }

        return $this->convertSeparateDMStoDD($dmsArray['deg'], $dmsArray['min'], $dmsArray['sec'], $dmsMatch[0][2]);
    }

    /**
     * @param string $min
     * @param string $sec
     * @param string $direction
     */
    public function convertSeparateDMStoDD($deg, $min, $sec, $direction)
    {
        $dd = $deg + ((($min * 60) + ($sec)) / 3600);

        if ($direction == "S" || $direction == "W") {
            $dd = $dd * -1;
        }

        return $dd;
    }

    public function convertDDtoDMS($dec)
    {
        $vars = explode(".", $dec);
        $deg = $vars[0];
        $tempma = "0.".$vars[1];

        $tempma = $tempma * 3600;
        $min = floor($tempma / 60);
        $sec = $tempma - ($min * 60);

        return array("deg" => $deg, "min" => $min, "sec" => $sec);
    }


}
