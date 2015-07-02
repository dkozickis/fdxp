<?php

namespace AppBundle\Utils;

use AppBundle\Entity\ShiftLogOnShift;
use Doctrine\Common\Persistence\ManagerRegistry;

class ShiftLogUtils
{
    private $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    public function currentHours()
    {
        $time_now = new \DateTime('now');
        $hours_now = $time_now->format('G');

        return $hours_now;
    }

    public function currentShift()
    {
        $hoursNow = $this->currentHours();
        $currentShift = '';

        if ($hoursNow >= 3 && $hoursNow < 15) {
            $currentShift = 'D';
        } elseif ($hoursNow >= 15 || $hoursNow < 3) {
            $currentShift = 'N';
        }

        return $currentShift;
    }

    public function getPersonnelOnShift($date = NULL, $shift = NULL)
    {
        $date = isset($date) ? $date : new \DateTime('now');
        $shift = isset($shift) ? $shift : $this->currentShift();
        $em = $this->managerRegistry->getManager();

        $info = file_get_contents("http://ey.lidousers.com/roster/index.php/roster/on_shift/"
            .$date->format('Y')
            ."/".$date->format('m')
            ."/".$date->format('j')
            ."/".$shift);
        $newOnShift = new ShiftLogOnShift();
        $newOnShift->setShiftDate($date);
        $newOnShift->setShiftPeriod($shift);
        $newOnShift->setOnShift(json_decode($info, TRUE));
        $em->persist($newOnShift);
        $em->flush();

        return $newOnShift;
    }

    public function archiveDateShiftProposal($hours_now = null)
    {
        $proposal = [];

        if ($hours_now === null) {
            $hours_now = $this->currentHours();
        }

        if (in_array($hours_now, array(2, 3))) {
            $proposal['shift'] = 'N';
            $archivedDate = new \DateTime('now');
            $proposal['date'] = $archivedDate->modify('-1 day');
        } elseif (in_array($hours_now, array(14, 15))) {
            $proposal['shift'] = 'D';
            $proposal['date'] = new \DateTime('now');
        } else {
            $proposal['shift'] = false;
            $proposal['date'] = false;
        }

        return $proposal;
    }

    public function showArchiveButton($hours_now = null)
    {
        if ($hours_now === null) {
            $hours_now = $this->currentHours();
        }

        $proposer = $this->archiveDateShiftProposal($hours_now);

        if (in_array($hours_now, array(2, 3, 14, 15)) && !empty($proposer['date'])) {
            $checker = $this->managerRegistry->getManager()
                ->getRepository('AppBundle:ShiftLogArchive')->checkExistsShiftReport($proposer['date'],
                $proposer['shift']);
            if (!$checker) {
                $activate = 1;
            } else {
                $activate = 0;
            }
        } else {
            $activate = 0;
        }

        return $activate;
    }

    public function mainePageInit()
    {
        $proposer = $this->archiveDateShiftProposal();
        $showButton = $this->showArchiveButton();

        if (!empty($proposer['date'])) {
            $shift_text = strtoupper($proposer['date']->format('dMy')).' '.$proposer['shift'];
        } else {
            $shift_text = '';
        }

        if ($showButton === 0) {
            $menu_state = 'hidden';
        } else {
            $menu_state = '';
        }

        return array('text' => $shift_text, 'menu_state' => $menu_state);
    }
}
