<?php

namespace AppBundle\Listener;
use AppBundle\Entity\Comparison;
use AppBundle\Entity\ComparisonCase;
use Doctrine\ORM\Event\OnFlushEventArgs;

class ComparisonListener
{
    public function onFlush(OnFlushEventArgs $args)
    {

        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();
        $case = new ComparisonCase();

        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            if($entity instanceof Comparison) {
                $case->setName('Basic');
                $case->setBasic('1');
                $case->setComparison($entity);

                $em->persist($case);
            }
        }

        $uow->computeChangeSets();
    }
}
