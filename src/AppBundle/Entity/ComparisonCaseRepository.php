<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ComparisonCaseRepository extends EntityRepository{

    public function findCaseCalcs($comp_id){

        $qb = $this->createQueryBuilder('caser', 'calculations')
            ->select('caser', 'calculations')
            ->where('caser.comparison = :comp_id')
            ->setParameter('comp_id', $comp_id)
            ->innerJoin('caser.calcs', 'calculations')
            ->orderBy('caser.basic', 'DESC')
            ->addOrderBy('calculations.citypair', 'ASC')
            ->getQuery();

        return $qb->getArrayResult();
    }

}