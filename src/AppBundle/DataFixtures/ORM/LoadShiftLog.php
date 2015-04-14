<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\ShiftLog;

class LoadShiftLog implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $shiftLog = new ShiftLog();

        $shiftLog->setInfoType('info_ops');
        $shiftLog->setContent('OPERATIONAL INFORMATION CURRENT');
        $shiftLog->setInfoHeader('Operational information');
        $manager->persist($shiftLog);
        $manager->flush();


        $shiftLog = new ShiftLog();
        $shiftLog->setInfoType('info_wx');
        $shiftLog->setContent('WX INFORMATION CURRENT');
        $shiftLog->setInfoHeader('Weather information');
        $manager->persist($shiftLog);
        $manager->flush();


        $shiftLog = new ShiftLog();
        $shiftLog->setInfoType('info_notam');
        $shiftLog->setContent('NOTAM INFORMATION CURRENT');
        $shiftLog->setInfoHeader('NOTAM information');
        $manager->persist($shiftLog);
        $manager->flush();

        $shiftLog = new ShiftLog();
        $shiftLog->setInfoType('info_other');
        $shiftLog->setContent('OTHER INFORMATION CURRENT');
        $shiftLog->setInfoHeader('Other information');
        $manager->persist($shiftLog);
        $manager->flush();

    }
}