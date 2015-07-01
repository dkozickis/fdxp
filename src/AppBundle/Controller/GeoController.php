<?php

namespace AppBundle\Controller;

use GeoJson\Geometry\LineString;
use GeoJson\Geometry\MultiPolygon;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use GeoJson\Geometry\LinearRing;
use GeoJson\Geometry\Polygon;

/**
 * Class GeoController.
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
        $notamPolygons = [];
        $geoUtils = $this->get('app.geo_utils');

        $areaText[] = "101000N   0823500E
        103500N 0830000E
        095500N 0833000E
        093000N 0830500E
        101000N   0823500E";

        $areaText[] = "082500N 0832000E
        082500N 0835000E
        073500N 0835000E
        073500N 0832000E
        082500N 0832000E";

        $areaText[] = "054000S 0820000E
        054000S 0831000E
        073000S 0830000E
        073000S 0815000E
        054000S 0820000E";

        $areaText[] = "404500S 0765500E
        404500S 0791500E
        464500S 0782500E
        464500S 0760500E
        404500S 0765500E";

        foreach($areaText as $key => $area){
            $coordinates = array();
            $areaDMSCoordinates = explode("\n", $area);
            foreach ($areaDMSCoordinates as $DMSCoordinates) {
                preg_match('~(?<lat>\w+)\s+(?<lon>\w+)~', $DMSCoordinates, $DMSArray);
                $coordinates[] = [
                    $geoUtils->convertDMStoDD($DMSArray['lon']),
                    $geoUtils->convertDMStoDD($DMSArray['lat'])];
            }
            $notamPolygons[] = new Polygon(array(
                new LinearRing($coordinates)
            ));
        }

        $final = new MultiPolygon($notamPolygons);

        $geoJSON = json_encode($final);

        $response = new Response($geoJSON);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
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
