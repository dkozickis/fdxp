<?php

namespace AppBundle\Entity;

use AppBundle\Entity\FlightWatch;

/**
 * FlightwatchInfo
 */
class FlightWatchInfo
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
     * @var FlightWatch
     */
    private $flight;

    /**
     * @var string
     */
    private $etoInfo = 'info';

    /** @var  \DateTimeImmutable */
    private $etoTime;

    private $form;
    /**
     * @var string
     */
    private $airportsString;
    /**
     * @var array
     */
    private $wxInfo;
    /**
     * @var \DateTime
     */
    private $wxTime;

    /**
     * @return mixed
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @param mixed $form
     */
    public function setForm($form)
    {
        $this->form = $form;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getEtoTime()
    {
        return $this->etoTime;
    }

    /**
     * @param \DateTimeImmutable $etoTime
     */
    public function setEtoTime($etoTime)
    {
        $this->etoTime = $etoTime;
    }

    /**
     * @return string
     */
    public function getAirportsString()
    {
        return $this->airportsString;
    }

    /**
     * @param string $airportsString
     */
    public function setAirportsString($airportsString)
    {
        $this->airportsString = $airportsString;
    }

    /**
     * @return string
     */
    public function getEtoInfo()
    {
        return $this->etoInfo;
    }

    /**
     * @param string $etoInfo
     */
    public function setEtoInfo($etoInfo)
    {
        $this->etoInfo = $etoInfo;
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
     * Get pointType
     *
     * @return string
     */
    public function getPointType()
    {
        return $this->pointType;
    }

    /**
     * Set pointType
     *
     * @param string $pointType
     * @return FlightWatchInfo
     */
    public function setPointType($pointType)
    {
        $this->pointType = $pointType;

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
     * Set pointName
     *
     * @param string $pointName
     * @return FlightWatchInfo
     */
    public function setPointName($pointName)
    {
        $this->pointName = $pointName;

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
     * Set eto
     *
     * @param \DateTime $eto
     * @return FlightWatchInfo
     */
    public function setEto($eto)
    {
        $this->eto = $eto;

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
     * Set ebo
     *
     * @param float $ebo
     * @return FlightWatchInfo
     */
    public function setEbo($ebo)
    {
        $this->ebo = $ebo;

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
     * Set airports
     *
     * @param array $airports
     * @return FlightWatchInfo
     */
    public function setAirports($airports)
    {
        $this->airports = $airports;

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
     * @return FlightWatchInfo
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
     * @return FlightWatchInfo
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
     * @return FlightWatchInfo
     */
    public function setCompletedBy($completedBy)
    {
        $this->completed_by = $completedBy;

        return $this;
    }

    /**
     * Get flight
     *
     * @return FlightWatch
     */
    public function getFlight()
    {
        return $this->flight;
    }

    /**
     * Set flight
     *
     * @param FlightWatch $flight
     * @return FlightWatchInfo
     */
    public function setFlight(FlightWatch $flight = null)
    {
        $this->flight = $flight;

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
     * Set wxInfo
     *
     * @param array $wxInfo
     * @return FlightWatchInfo
     */
    public function setWxInfo($wxInfo)
    {
        $this->wxInfo = $wxInfo;

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

    /**
     * Set wxTime
     *
     * @param \DateTime $wxTime
     * @return FlightWatchInfo
     */
    public function setWxTime($wxTime)
    {
        $this->wxTime = $wxTime;

        return $this;
    }

    /**
     * @param $wxInfo
     * @param $wxTime
     * @return FlightWatchInfo
     */
    public function setWxInfoAndTime($wxInfo, $wxTime)
    {

        $this->wxTime = $wxTime;
        $this->wxInfo = $wxInfo;

        return $this;
    }


    public function setPointInfo($fw, $info)
    {

        $this->setFlight($fw);
        $this->setEto(new \DateTime($info['time']));
        $this->setPointName($info['name']);
        $this->setPointType('etops');
        $this->setAirports($info['airports']);

        return $this;
    }

    public function setDpInfo($fw, $dpInfo, $airports)
    {

        $this->setFlight($fw);
        $this->setEto(new \DateTime($dpInfo['time']));
        $this->setPointType('dp');
        $this->setPointName($dpInfo['name']);
        $this->setEbo($dpInfo['fob']);
        $this->setAirports($airports);

        return $this;
    }
}
