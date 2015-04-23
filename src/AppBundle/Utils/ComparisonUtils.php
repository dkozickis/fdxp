<?php

namespace AppBundle\Utils;

class ComparisonUtils
{

    public function platoToArray($plato)
    {
        $parsed_info = [];

        $data = str_getcsv($plato, "\n"); //parse the rows
        foreach($data as &$row)
        {
            $row = str_getcsv($row, "|");
            $row = array_filter(array_map("trim", $row),'strlen');
            if(count($row) > 0) {
                $counter = count($row);
                if ($row[0] == 'Dep-Dest') {
                    for ($i = 1; $i < $counter; $i++) {
                        $parsed_info[$i-1] = array('airport_pair' => $row[$i]);
                    }
                }
                if ($row[0] == 'Total Costs'){
                    for ($i = 1; $i < $counter; $i++) {
                        $parsed_info[$i-1]['t_costs'] = $row[$i];
                    }
                }
                if ($row[0] == 'Trip Time'){
                    for ($i = 1; $i < $counter; $i++) {
                        $parsed_info[$i-1]['t_time'] = $row[$i];
                    }
                }
            }

        }

        return $parsed_info;
    }

}