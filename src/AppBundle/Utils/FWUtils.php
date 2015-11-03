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

    public function addNewFlight($flightInfo, $pointInfo, $dpInfo, $erdErda, $desk){

        $em = $this->managerRegistry->getManager();
        $fw = new Flightwatch();
        $fw->setMainData($flightInfo, $desk);
        $em->persist($fw);

        foreach ($pointInfo as $value) {
            $fwInfo = new FlightwatchInfo();
            $fwInfo->setPointInfo($fw, $value);
            $em->persist($fwInfo);
        }

        if ($dpInfo) {

            $airports = array($flightInfo['dest']);

            if($flightInfo['altn'] != 'NIL'){
                $airports[] = $flightInfo['altn'];
            }

            if(isset($erdErda['erd'])){
                $airports[] = $erdErda['erd'];
            }

            if(isset($erdErda['erda'])){
                $airports[] = $erdErda['erda'];
            }

            $fw->setErdErda($erdErda['erd'], $erdErda['erda']);
            $fwInfo = new FlightwatchInfo();
            $fwInfo->setDpInfo($fw, $dpInfo, $airports);
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
