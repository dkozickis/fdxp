<?php

namespace AppBundle\Listener;

use AppBundle\Entity\FlightWatch;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;

class FlightWatchListener
{

    public function preUpdate(PreUpdateEventArgs $eventArgs)
    {
        /** @var FlightWatch $entity */
        $entity = $eventArgs->getEntity();
        $em = $eventArgs->getEntityManager();

        if ($entity instanceof FlightWatch) {
            if ($eventArgs->hasChangedField('takeOffTime')) {


                $takeOffTime = $entity->getTakeOffTime();
                $immutableTOT = \DateTimeImmutable::createFromMutable($takeOffTime);

                /** @var FlightwatchInfo $dp */
                $dp = $em->getRepository('AppBundle:FlightwatchInfo')->findOneBy(array(
                    'pointType' => 'dp',
                    'flight' => $entity->getId()
                ));

                if($dp){
                    $dpTripTime = new \DateInterval($dp->getEto()->format('\P\TG\Hi\M'));
                    $entity->setDpTime($immutableTOT->add($dpTripTime));
                }

            }
        }
    }


}
