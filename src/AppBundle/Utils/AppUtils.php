<?php

namespace AppBundle\Utils;

use Doctrine\ORM\EntityManager;

class AppUtils
{
    protected $em;

    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        $this->em = $em;
    }

    public function currentHours()
    {
        $time_now = new \DateTime("now");
        $hours_now = $time_now->format('G');

        return $hours_now;
    }

    public function archiveDateShiftProposal($hours_now = null)
    {
        if($hours_now === NULL)
        {
            $hours_now = $this->currentHours();
        }

        if(in_array($hours_now, array(2, 3))){
            $proposal['shift'] = 'N';
            $archivedDate = new \DateTime('now');
            $proposal['date'] = $archivedDate->modify('-1 day');
        }elseif(in_array($hours_now, array(14, 15))){
            $proposal['shift'] = 'D';
            $proposal['date'] = new \DateTime('now');
        }else{
            $proposal['shift'] = FALSE;
            $proposal['date'] = FALSE;
        }

        return $proposal;
    }

    public function showArchiveButton($hours_now = null)
    {
        if($hours_now === NULL)
        {
            $hours_now = $this->currentHours();
        }

        $proposer = $this->archiveDateShiftProposal($hours_now);

        if(in_array($hours_now, array(2, 3, 14, 15)) && !empty($proposer['date']))
        {
            $checker = $this->em->getRepository('AppBundle:ShiftLogArchive')->checkExistsShiftReport($proposer['date'],
                $proposer['shift']);
            if(!$checker){
                $activate = 1;
            }else {
                $activate = 0;
            }
        }else{
            $activate = 0;
        }

        return $activate;
    }

}