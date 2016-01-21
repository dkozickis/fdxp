<?php

namespace AppBundle\Utils;

use Doctrine\Common\Persistence\ManagerRegistry;

class ComparisonUtils
{

    private $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }
    
    public function platoToArray($plato)
    {
        $parsed_info = [];

        $data = str_getcsv($plato, "\n"); //parse the rows
        foreach ($data as &$row) {
            $row = str_getcsv($row, '|');
            $row = array_filter(array_map('trim', $row), 'strlen');
            if (count($row) > 0) {
                $counter = count($row);
                for ($i = 1; $i < $counter; $i++) {
                    switch ($row[0]) {
                        case 'Dep-Dest':
                            $parsed_info[$i - 1] = array('airport_pair' => $row[$i]);
                            break;
                        case 'Total Costs':
                            $parsed_info[$i - 1]['t_costs'] = $row[$i];
                            break;
                        case 'Trip Time':
                            $parsed_info[$i - 1]['t_time'] = $row[$i];
                            break;
                    }
                }
            }
        }

        return $parsed_info;
    }

    public function summaryHeaderBuilder($header, $case)
    {
        if ($case['basic']) {
            $header[] = 'Basic cost';
            $header[] = 'Basic time';
        } else {
            $header[] = $case['name'].' cost';
            $header[] = $case['name'].' cost difference';
            $header[] = $case['name'].' time';
            $header[] = $case['name'].' time difference';
        }

        return $header;
    }

    public function atcToLatLon($route){

        $coords = [];
        $rte_wpts = [];

        // Get all waypoint (5 A-Z) matches from route
        preg_match_all('/[A-Z]{5}/', $route, $waypoints);

        // Find all waypoints in DB
        $coordinates = $this->managerRegistry->getManager()->getRepository('AppBundle:Waypoints')->findBy(array(
            'wpt_id' => $waypoints[0]
        ));

        // Create array of waypoints that are in same order as in route
        foreach ($waypoints[0] as $wpt) {
            $rte_wpts[$wpt]['name'] = $wpt;
        }

        // Write waypoint infromation from DB to array
        foreach ($coordinates as $wpt_coords) {
            $rte_wpts[$wpt_coords->getWptId()]['lat'] = $wpt_coords->getLat();
            $rte_wpts[$wpt_coords->getWptId()]['lon'] = $wpt_coords->getLon();
        }

        // Go through waypoint array and push lat&lon pair to other array used for GeoJSON
        foreach ($rte_wpts as $key => $wpt) {
            array_push($coords, array($wpt['lon'], $wpt['lat']));
        }

        return $coords;

    }
}
