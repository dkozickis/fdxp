<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use GeoJson\Geometry\LinearRing;
use GeoJson\Geometry\Polygon;

/**
 * Class geoController.
 *
 * @Route("/geo")
 */
class GeoController extends Controller
{
    /**
     * @return Response
     * @Route("/")
     *
     * @Method({"GET"})
     */
    public function indexAction()
    {
        return new Response();
    }

    /**
     * @return Response
     * @Route("/coordinates/to-area", name="geo_coordinates_to_area")
     *
     * @Method({"GET"})
     */
    public function coordinatesToAreaAction()
    {

        $geoUtils = $this->get('app.geo_utils');
        $coordinates = [];

        $areaText = "090118N   0922702E
        092259N   0930324E
        085117N   0941745E
        081409N   0942605E
        073112N   0940132E
        071344N   0931632E
        080431N  0933811E
        083203N   0933831E
        084514N   0932219E
        090118N   0922702E";

        $areaDMSCoordinates = explode("\n", $areaText);

        foreach ($areaDMSCoordinates as $DMSCoordinates) {
            preg_match('~(?<lat>\w+)\s+(?<lon>\w+)~', $DMSCoordinates, $DMSArray);
            $coordinates[] = [
                $geoUtils->DMStoDD($DMSArray['lon']),
                $geoUtils->DMStoDD($DMSArray['lat'])];
        }


        $polygon = new Polygon(array(
            new LinearRing($coordinates)
        ));


        $json = json_encode($polygon);

        dump($json);

        throw new \Exception('');

        return new Response();
    }

    /**
     * @Route("/csv/{flightNo}")
     *
     * @Method({"GET"})
     */
    public function generateCSVAction($flightNo)
    {
        $geoUtils = $this->get('app.geo_utils');
        $flightIDS = $geoUtils->fetchDataID($flightNo);
        $csv_out = '';
        $response = new Response();

        $a = 0;
        foreach ($flightIDS as $value) {
            $flight_info_json = file_get_contents('http://mobile.api.fr24.com/common/v1/flight-playback.json?flightId='.$value);
            $flight_info = json_decode($flight_info_json);
            foreach ($flight_info->result->response->data->flight->track as $wpt_info) {
                $csv_out .= $a++
                            .','.$wpt_info->longitude
                            .','.$wpt_info->latitude
                            .','.$wpt_info->altitude->feet."\n";
            }
        }

        $response->setStatusCode(200);
        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');

        $response->setContent($csv_out);

        return $response;
    }
}
