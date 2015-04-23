<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Comparison;
use AppBundle\Entity\ComparisonCase;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadComparison implements FixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $comparison = new Comparison();
        $comparison->setName('Iran closed');

        $c_case = new ComparisonCase();
        $c_case->setName('Basic');
        $c_case->setBasic(1);
        $c_case->setComparison($comparison);

        $manager->persist($comparison);
        $manager->persist($c_case);

        $manager->flush();

    }

}
