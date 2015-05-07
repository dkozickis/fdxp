<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * FlightwatchRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class FlightwatchRepository extends EntityRepository
{
    public function findAllWithInfo() {

        return $this->createQueryBuilder('f')
            ->addSelect('i')
            ->join('f.info', 'i')
            ->where('f.completed is null')
            ->andWhere('i.pointName not like :pointName')
            ->setParameter('pointName', 'EXP%')
            ->orderBy('f.flightDate', 'ASC')
            ->addOrderBy('f.std', 'ASC')
            ->getQuery()
            ->setHint(\Doctrine\ORM\Query::HINT_INCLUDE_META_COLUMNS, true)
            ->getArrayResult();

    }
}
