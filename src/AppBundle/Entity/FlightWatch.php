<?php

namespace AppBundle\Entity;

use AppBundle\Entity\FlightWatchInfo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Flightwatch
 */
class FlightWatch
{

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $flightNumber;

    /**
     * @var string
     */
    private $dep;

    /**
     * @var string
     */
    private $dest;

    /**
     * @var \DateTime
     */
    private $flightDate;

    /**
     * @var \DateTime
     */
    private $takeOffTime;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $info;

    private $finalizeForm;
    private $deleteForm;
    /**
     * @var \DateTime
     */
    private $std;
    /**
     * @var string
     */
    private $altn;
    /**
     * @var string
     */
    private $erd;
    /**
     * @var string
     */
    private $erda;
    /**
     * @var boolean
     */
    private $completed;
    /**
     * @var \DateTime
     */
    private $completed_at;
    /**
     * @var string
     */
    private $completed_by;
    /**
     * @var integer
     */
    private $desk;
    /**
     * @var \DateTime
     */
    private $dpTime;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->info = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getFinalizeForm()
    {
        return $this->finalizeForm;
    }

    /**
     * @param mixed $finalizeForm
     */
    public function setFinalizeForm($finalizeForm)
    {
        $this->finalizeForm = $finalizeForm;
    }

    /**
     * @return mixed
     */
    public function getDeleteForm()
    {
        return $this->deleteForm;
    }

    /**
     * @param mixed $deleteForm
     */
    public function setDeleteForm($deleteForm)
    {
        $this->deleteForm = $deleteForm;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get flightNumber
     *
     * @return string
     */
    public function getFlightNumber()
    {
        return $this->flightNumber;
    }

    /**
     * Set flightNumber
     *
     * @param string $flightNumber
     * @return FlightWatch
     */
    public function setFlightNumber($flightNumber)
    {
        $this->flightNumber = $flightNumber;

        return $this;
    }

    /**
     * Get dep
     *
     * @return string
     */
    public function getDep()
    {
        return $this->dep;
    }

    /**
     * Set dep
     *
     * @param string $dep
     * @return FlightWatch
     */
    public function setDep($dep)
    {
        $this->dep = $dep;

        return $this;
    }

    /**
     * Get dest
     *
     * @return string
     */
    public function getDest()
    {
        return $this->dest;
    }

    /**
     * Set dest
     *
     * @param string $dest
     * @return FlightWatch
     */
    public function setDest($dest)
    {
        $this->dest = $dest;

        return $this;
    }

    /**
     * Get flightDate
     *
     * @return \DateTime
     */
    public function getFlightDate()
    {
        return $this->flightDate;
    }

    /**
     * Set flightDate
     *
     * @param \DateTime $flightDate
     * @return FlightWatch
     */
    public function setFlightDate($flightDate)
    {
        $this->flightDate = $flightDate;

        return $this;
    }

    /**
     * Get takeOffTime
     *
     * @return \DateTime
     */
    public function getTakeOffTime()
    {
        return $this->takeOffTime;
    }

    /**
     * Set takeOffTime
     *
     * @param \DateTime $takeOffTime
     * @return FlightWatch
     */
    public function setTakeOffTime($takeOffTime)
    {
        $this->takeOffTime = $takeOffTime;

        return $this;
    }

    /**
     * Add info
     *
     * @param FlightWatchInfo $info
     * @return FlightWatch
     */
    public function addInfo(FlightWatchInfo $info)
    {
        $this->info[] = $info;

        return $this;
    }

    /**
     * Remove info
     *
     * @param FlightWatchInfo $info
     */
    public function removeInfo(FlightWatchInfo $info)
    {
        $this->info->removeElement($info);
    }

    /**
     * Get info
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * Get std
     *
     * @return \DateTime
     */
    public function getStd()
    {
        return $this->std;
    }

    /**
     * Set std
     *
     * @param \DateTime $std
     * @return FlightWatch
     */
    public function setStd($std)
    {
        $this->std = $std;

        return $this;
    }

    /**
     * Get altn
     *
     * @return string
     */
    public function getAltn()
    {
        return $this->altn;
    }

    /**
     * Set altn
     *
     * @param string $altn
     * @return FlightWatch
     */
    public function setAltn($altn)
    {
        $this->altn = $altn;

        return $this;
    }

    /**
     * Get erd
     *
     * @return string
     */
    public function getErd()
    {
        return $this->erd;
    }

    /**
     * Set erd
     *
     * @param string $erd
     * @return FlightWatch
     */
    public function setErd($erd)
    {
        $this->erd = $erd;

        return $this;
    }

    /**
     * Get erda
     *
     * @return string
     */
    public function getErda()
    {
        return $this->erda;
    }

    /**
     * Set erda
     *
     * @param string $erda
     * @return FlightWatch
     */
    public function setErda($erda)
    {
        $this->erda = $erda;

        return $this;
    }

    /**
     * Get completed
     *
     * @return boolean
     */
    public function getCompleted()
    {
        return $this->completed;
    }

    /**
     * Set completed
     *
     * @param boolean $completed
     * @return FlightWatch
     */
    public function setCompleted($completed)
    {
        $this->completed = $completed;

        return $this;
    }

    /**
     * Get completed_at
     *
     * @return \DateTime
     */
    public function getCompletedAt()
    {
        return $this->completed_at;
    }

    /**
     * Set completed_at
     *
     * @param \DateTime $completedAt
     * @return FlightWatch
     */
    public function setCompletedAt($completedAt)
    {
        $this->completed_at = $completedAt;

        return $this;
    }

    /**
     * Get completed_by
     *
     * @return string
     */
    public function getCompletedBy()
    {
        return $this->completed_by;
    }

    /**
     * Set completed_by
     *
     * @param string $completedBy
     * @return FlightWatch
     */
    public function setCompletedBy($completedBy)
    {
        $this->completed_by = $completedBy;

        return $this;
    }

    public function setMainData($flightInfo, $desk){

        $this->setFlightNumber($flightInfo['atcCs']);
        $this->setDep($flightInfo['dep']);
        $this->setDest($flightInfo['dest']);
        $this->setFlightDate(new \DateTime($flightInfo['dof']));
        $this->setStd(new \DateTime($flightInfo['std']));
        $this->setAltn($flightInfo['altn']);
        $this->setDesk($desk);

        return $this;

    }

    public function setErdErda($erd, $erda){

        $this->setErd($erd);
        $this->setErda($erda);

        return $this;
    }

    /**
     * Get desk
     *
     * @return integer 
     */
    public function getDesk()
    {
        return $this->desk;
    }

    /**
     * Set desk
     *
     * @param integer $desk
     * @return FlightWatch
     */
    public function setDesk($desk)
    {
        $this->desk = $desk;

        return $this;
    }

    /**
     * Get dpTime
     *
     * @return \DateTime
     */
    public function getDpTime()
    {
        return $this->dpTime;
    }

    /**
     * Set dpTime
     *
     * @param \DateTime $dpTime
     * @return FlightWatch
     */
    public function setDpTime($dpTime)
    {
        $this->dpTime = $dpTime;

        return $this;
    }
}
