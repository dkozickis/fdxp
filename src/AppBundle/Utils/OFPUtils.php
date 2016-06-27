<?php

namespace AppBundle\Utils;

class OFPUtils
{

    public function getOFPLines($ofp)
    {
        return explode("\n", $ofp);
    }

    public function getAvgFF($ofp)
    {

        preg_match_all('~AVG FF KG\/HR\s+([0-9]{3,5})~', $ofp, $avg_ff);

        return $avg_ff[1];

    }

    /**
     * @param $ofp
     * @return array|bool
     */
    public function getDPInfo($ofp)
    {
        $dp_array = [];
        if (preg_match('~DECISION POINT:\s+([A-Z0-9]{2,5}|[0-9]{4}[S,N,W,E]{1})~', $ofp, $dp_matches)) {
            $ofp_lines = $this->getOFPLines($ofp);
            $dp = trim($dp_matches[1]);

            foreach ($ofp_lines as $line_number => $line) {
                if (strpos($line, '|DP '.$dp)) {
                    for ($i = $line_number; $i != ($line_number - 10); --$i) {
                        $dp_ebo = $this->getEBOFromLine($ofp_lines[$i]);
                        if ($dp_ebo) {
                            $dp_array['name'] = $dp;
                            $dp_array['fob'] = $dp_ebo[1];
                            $dp_eto = $this->getETOFromLine($ofp_lines[$i - 1]);
                            $dp_array['time'] = $dp_eto[1];
                            break;
                        }
                    }
                }
            }

            return $dp_array;
        } else {
            return false;
        }
    }

    public function getErdErda($ofp) {

        $dp_info = [];

        if (preg_match('~ENROUTE DEST:\s+([A-Z]{4})/~', $ofp, $matches)) {
            $dp_info['erd'] = $matches[1];
            preg_match('~ENROUTE DEST ALTN:\s+([A-Z]{4})/~', $ofp, $matches);
            $dp_info['erda'] = $matches[1];

            return $dp_info;
        } else {
            return false;
        }

    }

    /**
     * @param $ofp
     * @return array
     * @throws \Exception
     */
    public function getETOPSInfo($ofp) {

        $nav = $this->getETOPSInfoFromNav($ofp);
        $portion = $this->getETOPSInfoFromPortion($ofp);

        if (count($nav) == count($portion)) {
            foreach ($portion as $key => $value) {
                $nav[$key]['name'] = $value[1];
                $nav[$key]['airports'] = array_unique(explode("/", $value[2]));
            }
        } else {
            throw new \Exception('Amount of ETOPS info in Nav and ETOPS OFP is not equal');
        }

        return $nav;
    }

    public function getETOPSInfoFromNav($ofp)
    {

        $etops_info = [];

        preg_match_all(
            '~EDTO (EEP [0-9]?|EXP [0-9]?|ETP [0-9]|ETP [0-9]-[0-9])\s+((N|S)[0-9]{4}.[0-9]\s+\/\s+(E|W)[0-9]{5}.[0-9])\s+ETA\s+([0-9]{4})~',
            $ofp, $etops_matches, PREG_SET_ORDER);

        foreach ($etops_matches as $value) {
            $etops_info[] = array('name' => $value[1], 'time' => $value[5]);
        }

        return $etops_info;

    }

    public function getETOPSInfoFromPortion($ofp) {

        preg_match_all(
            '~(EEP[0-9]|EXP[0-9]|ETP[0-9]|ETP[0-9]-[0-9])\s([A-Z]{4}\/[A-Z]{0,4}).+?(DX|DC|1X).{28}\s*([0-9.]+)\s+~',
            $ofp, $etops_matches, PREG_SET_ORDER);

        return $etops_matches;

    }

    public function getEBOFromLine($line)
    {
        preg_match('~\s*([0-9]{2,3}\.[0-9]{1})\|\s{5}\|~', $line, $ebo_matches);
        return $ebo_matches;

    }

    public function getETOFromLine($line)
    {
        preg_match('~([0-9]{4})\s_____\|\s?[0-9]{1,3}\.[0-9]{1}\|_____\|____~', $line, $eto_matches);
        return $eto_matches;
    }

    public function getATCCS($ofp) {

        preg_match_all('~ATC C\/S ([A-Z]{3}[0-9A-Z]{1,4})\s~', $ofp, $atc_cs_preg, PREG_SET_ORDER);

        return $atc_cs_preg[0][1];

    }

    public function getDepDest($ofp) {
        preg_match_all('~([A-Z]{4})\/[A-Z]{3}\s+([A-Z]{4})\/[A-Z]{3}\s+GND~', $ofp, $route_preg, PREG_SET_ORDER);

        return array('dep' => $route_preg[0][1], 'dest' => $route_preg[0][2]);
    }

    public function getDOF($ofp) {
        preg_match_all('~ATC C\/S ETD[0-9A-Z]{1,4}\s+([0-9]{2}[A-Z]{3}[0-9]{4})~', $ofp, $dof, PREG_SET_ORDER);

        return $dof[0][1];
    }

    public function getSTD($ofp) {
        preg_match_all('~STD ([0-9]{4})Z~', $ofp, $std);

        return $std[1][0];
    }

    public function getAltn($ofp) {
        preg_match('~ALTN\s+([A-Z]{3,4})~', $ofp, $matches);
        return $matches[1];
    }

    public function getMainInfo($ofp) {

        $atcCs = $this->getATCCS($ofp);
        $depDest = $this->getDepDest($ofp);
        $dof = $this->getDOF($ofp);
        $std = $this->getSTD($ofp);
        $altn = $this->getAltn($ofp);

        return array('atcCs' => $atcCs,
            'dep' => $depDest['dep'],
            'dest' => $depDest['dest'],
            'dof' => $dof,
            'std' => $std,
            'altn' => $altn);
    }
}
