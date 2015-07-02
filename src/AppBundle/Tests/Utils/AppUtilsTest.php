<?php

namespace AppBundle\Tests\Utils;

use AppBundle\Utils;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AppUtilsTest extends KernelTestCase
{
    public $em;

    public function setUp()
    {
        self::bootKernel();
        $this->em = static::$kernel->getContainer()->get('doctrine')->getEntityManager();
    }

    public function testProposalNight()
    {
        $appUtils = new Utils\ShiftLogUtils($this->em);
        $proposal = $appUtils->archiveDateShiftProposal(2);

        $this->assertEquals('N', $proposal['shift']);
    }

    public function testProposalFalse()
    {
        $appUtils = new Utils\ShiftLogUtils($this->em);
        $proposal = $appUtils->archiveDateShiftProposal(16);

        $this->assertEquals(false, $proposal['shift']);
    }

    public function testProposalDefault()
    {
        $appUtils = new Utils\ShiftLogUtils($this->em);
        $proposal = $appUtils->archiveDateShiftProposal();

        $this->assertArrayHasKey('shift', $proposal);
    }

    public function testButtonPositiveWithTime()
    {
        $appUtils = new Utils\ShiftLogUtils($this->em);
        $button = $appUtils->showArchiveButton(15);

        $this->assertEquals(1, $button);
    }

    public function testButtonNegativeWithTime()
    {
        $appUtils = new Utils\ShiftLogUtils($this->em);
        $button = $appUtils->showArchiveButton(10);

        $this->assertEquals(0, $button);
    }

    public function testButtonNegative()
    {
        $stub = $this->getMockBuilder('AppUtils')
            ->setMethods(array('currentHours'))
            ->getMock();

        $stub->method('currentHours')
            ->willReturn(5);

        $appUtils = new Utils\ShiftLogUtils($this->em);

        $button = $appUtils->showArchiveButton();

        $this->assertEquals(0, $button);
    }

    public function testButtonNegativeProposer()
    {
        $stubTime = $this->getMockBuilder('AppUtils')
            ->setMethods(array('currentHours'))
            ->getMock();

        $stubTime->method('currentHours')
            ->willReturn(15);

        $stub = $this->getMockBuilder('AppUtils')
            ->setMethods(array('archiveDateShiftProposal'))
            ->getMock();

        $stub->method('archiveDateShiftProposal')
            ->willReturn(array('date' => '22nov', 'shift'));

        $appUtils = new Utils\ShiftLogUtils($this->em);

        $button = $appUtils->showArchiveButton();

        $this->assertEquals(0, $button);
    }
}
