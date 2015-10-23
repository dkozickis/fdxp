<?php

namespace AppBundle\Listener;

use AppBundle\Entity\Flightwatch;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;

class FlightwatchListener
{

    public function preUpdate(PreUpdateEventArgs $eventArgs)
    {
        /** @var Flightwatch $entity */
        $entity = $eventArgs->getEntity();
        $em = $eventArgs->getEntityManager();

        if ($entity instanceof Flightwatch) {
            if ($eventArgs->hasChangedField('takeOffTime')) {


                $takeOffTime = $entity->getTakeOffTime();
                $immutableTOT = \DateTimeImmutable::createFromMutable($takeOffTime);

                /** @var FlightwatchInfo $dp */
                $dp = $em->getRepository('AppBundle:FlightwatchInfo')->findOneBy(array(
                    'pointType' => 'dp',
                    'flight' => $entity->getId()
                ));

                if($dp){
                    $dpTripTime = new \DateInterval($dp->getEto()->format('\P\Th\Hi\M'));
                    $entity->setDpTime($immutableTOT->add($dpTripTime));
                }

            }
        }
    }


}
