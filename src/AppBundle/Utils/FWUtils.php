<?php

namespace AppBundle\Utils;

use AppBundle\Entity\Flightwatch;
use AppBundle\Entity\FlightwatchInfo;
use Doctrine\Common\Persistence\ManagerRegistry;

class FWUtils {

    private $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    public function addNewFlight($flightInfo, $pointInfo, $dpInfo, $erdErda){

        $em = $this->managerRegistry->getManager();
        $fw = new Flightwatch();
        $fw->setMainData($flightInfo);
        $em->persist($fw);
        foreach ($pointInfo as $value) {
            $fwInfo = new FlightwatchInfo();
            $fwInfo->setPointInfo($fw, $value);
            $em->persist($fwInfo);
        }

        if ($dpInfo) {
            $fw->setErdErda($erdErda['erd'], $erdErda['erda']);
            $fwInfo = new FlightwatchInfo();
            $fwInfo->setDpInfo($fw, $dpInfo);
            $em->persist($fwInfo);
        }

        $em->flush();
        $em->clear();

        return true;
    }

    public function dangerOrWarning($interval){

        $status = 'info';

        if ($interval < 30) {
            $status = 'danger';
        } elseif ($interval < 60) {
            $status = 'warning';
        }

        return $status;
    }

}