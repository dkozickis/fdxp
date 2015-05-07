<?php

namespace AppBundle\Entity;

/**
 * FlightwatchInfo
 */
class FlightwatchInfo
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $pointType;

    /**
     * @var string
     */
    private $pointName;

    /**
     * @var \DateTime
     */
    private $eto;

    /**
     * @var float
     */
    private $ebo;

    /**
     * @var array
     */
    private $airports;

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
     * @var \AppBundle\Entity\Flightwatch
     */
    private $flight;


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
     * Set pointType
     *
     * @param string $pointType
     * @return FlightwatchInfo
     */
    public function setPointType($pointType)
    {
        $this->pointType = $pointType;

        return $this;
    }

    /**
     * Get pointType
     *
     * @return string 
     */
    public function getPointType()
    {
        return $this->pointType;
    }

    /**
     * Set pointName
     *
     * @param string $pointName
     * @return FlightwatchInfo
     */
    public function setPointName($pointName)
    {
        $this->pointName = $pointName;

        return $this;
    }

    /**
     * Get pointName
     *
     * @return string 
     */
    public function getPointName()
    {
        return $this->pointName;
    }

    /**
     * Set eto
     *
     * @param \DateTime $eto
     * @return FlightwatchInfo
     */
    public function setEto($eto)
    {
        $this->eto = $eto;

        return $this;
    }

    /**
     * Get eto
     *
     * @return \DateTime 
     */
    public function getEto()
    {
        return $this->eto;
    }

    /**
     * Set ebo
     *
     * @param float $ebo
     * @return FlightwatchInfo
     */
    public function setEbo($ebo)
    {
        $this->ebo = $ebo;

        return $this;
    }

    /**
     * Get ebo
     *
     * @return float 
     */
    public function getEbo()
    {
        return $this->ebo;
    }

    /**
     * Set airports
     *
     * @param array $airports
     * @return FlightwatchInfo
     */
    public function setAirports($airports)
    {
        $this->airports = $airports;

        return $this;
    }

    /**
     * Get airports
     *
     * @return array 
     */
    public function getAirports()
    {
        return $this->airports;
    }

    /**
     * Set completed
     *
     * @param boolean $completed
     * @return FlightwatchInfo
     */
    public function setCompleted($completed)
    {
        $this->completed = $completed;

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
     * Set completed_at
     *
     * @param \DateTime $completedAt
     * @return FlightwatchInfo
     */
    public function setCompletedAt($completedAt)
    {
        $this->completed_at = $completedAt;

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
     * Set completed_by
     *
     * @param string $completedBy
     * @return FlightwatchInfo
     */
    public function setCompletedBy($completedBy)
    {
        $this->completed_by = $completedBy;

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
     * Set flight
     *
     * @param \AppBundle\Entity\Flightwatch $flight
     * @return FlightwatchInfo
     */
    public function setFlight(\AppBundle\Entity\Flightwatch $flight = null)
    {
        $this->flight = $flight;

        return $this;
    }

    /**
     * Get flight
     *
     * @return \AppBundle\Entity\Flightwatch 
     */
    public function getFlight()
    {
        return $this->flight;
    }
    /**
     * @var array
     */
    private $wxInfo;

    /**
     * @var \DateTime
     */
    private $wxTime;


    /**
     * Set wxInfo
     *
     * @param array $wxInfo
     * @return FlightwatchInfo
     */
    public function setWxInfo($wxInfo)
    {
        $this->wxInfo = $wxInfo;

        return $this;
    }

    /**
     * Get wxInfo
     *
     * @return array 
     */
    public function getWxInfo()
    {
        return $this->wxInfo;
    }

    /**
     * Set wxTime
     *
     * @param \DateTime $wxTime
     * @return FlightwatchInfo
     */
    public function setWxTime($wxTime)
    {
        $this->wxTime = $wxTime;

        return $this;
    }

    /**
     * Get wxTime
     *
     * @return \DateTime 
     */
    public function getWxTime()
    {
        return $this->wxTime;
    }
}
