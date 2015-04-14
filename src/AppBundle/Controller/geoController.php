<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class geoController
 * @package AppBundle\Controller
 * @Route("/geo")
 */
class GeoController extends Controller
{
    /**
     * @return Response
     * @Route("/")
     */
    public function indexAction()
    {

        return new Response();
    }

    /**
     * @Route("/csv/{flightNo}")
     */
    public function generateCSVAction($flightNo)
    {

        $geoUtils = $this->get('app.geo_utils');
        $flightIDS = $geoUtils->fetchDataID($flightNo);
        $csv_out = '';
        $response = new Response();

        $a = 0;
        foreach($flightIDS as $value){
            $flight_info_json = file_get_contents('http://mobile.api.fr24.com/common/v1/flight-playback.json?flightId='.$value);
            $flight_info = json_decode($flight_info_json);
            foreach($flight_info->result->response->data->flight->track as $wpt_info)
            {
                $csv_out .= $a++
                            .",".$wpt_info->longitude
                            .",".$wpt_info->latitude
                            .",".$wpt_info->altitude->feet."\n";
            }
        }

        $response->setStatusCode(200);
        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');

        $response->setContent($csv_out);

        return $response;

    }


}