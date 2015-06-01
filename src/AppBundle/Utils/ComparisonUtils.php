<?php

namespace AppBundle\Utils;

class ComparisonUtils
{
    public function platoToArray($plato)
    {
        $parsed_info = [];

        $data = str_getcsv($plato, "\n"); //parse the rows
        foreach ($data as &$row) {
            $row = str_getcsv($row, '|');
            $row = array_filter(array_map('trim', $row), 'strlen');
            if (count($row) > 0) {
                $counter = count($row);
                for($i = 1; $i < $counter; $i++){
                    switch($row[0]){
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
}
