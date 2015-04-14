<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use GeoJson\GeoJson;

/**
 * Class geoController
 * @package AppBundle\Controller
 * @Route("/geo")
 */
class geoController extends Controller
{
    /**
     * @return Response
     * @Route("/")
     */
    public function indexAction()
    {

        $flight_info_json = file_get_contents('http://mobile.api.fr24.com/common/v1/flight-playback.json?flightId=5fb3ff8');
        $flight_info = json_decode($flight_info_json);

        foreach($flight_info->result->response->data->flight->track as $wpt_info)
        {
            //dump($wpt_info->latitude);
            $lineString[] = [$wpt_info->longitude, $wpt_info->latitude, $wpt_info->altitude->meters];
        }

        $multi = new \GeoJson\Geometry\LineString($lineString);

        $response = new JsonResponse();
        $response->setData($multi);

        return $response;
    }

    /**
     * @Route("/csv")
     */
    public function generateCSVAction()
    {

        $csv_out = '';
        $response = new Response();

        $flight_info_json = file_get_contents('http://mobile.api.fr24.com/common/v1/flight-playback.json?flightId=5fb3ff8');
        $flight_info = json_decode($flight_info_json);

        $response->setStatusCode(200);
        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');

        foreach($flight_info->result->response->data->flight->track as $wpt_info)
        {
            $csv_out .= $wpt_info->altitude->meters."\n";
        }

        $response->setContent($csv_out);

        return $response;

    }
}