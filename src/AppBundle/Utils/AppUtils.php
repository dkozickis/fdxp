<?php

namespace AppBundle\Utils;

use Doctrine\Common\Persistence\ManagerRegistry;

class AppUtils
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

    public function personnelOnShift()
    {
        $currentShift = $this->currentShift();
        $todayRoster = json_decode(file_get_contents('http://ey.lidousers.com/roster/index.php/roster/on_shift'));

        $onShift = [];

        foreach ($todayRoster as $shift_info) {
            if (strpos($shift_info->planned, $currentShift) !== false) {
                $onShift[] = $shift_info->f_name.' '.substr($shift_info->s_name, 0, 1);
            }
        }

        return $onShift;
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
