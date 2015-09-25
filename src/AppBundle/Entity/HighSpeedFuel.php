<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HighSpeedFuel
 */
class HighSpeedFuel
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var string
     */
    private $flightNumber;

    /**
     * @var string
     */
    private $depIATA;

    /**
     * @var string
     */
    private $destIATA;

    /**
     * @var \DateTime
     */
    private $normalFlightTime;

    /**
     * @var \DateTime
     */
    private $highSpeedFlightTime;

    /**
     * @var integer
     */
    private $normalCost;

    /**
     * @var integer
     */
    private $highSpeedCost;

    /**
     * @var boolean
     */
    private $archived;


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
     * Set date
     *
     * @param \DateTime $date
     * @return HighSpeedFuel
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set flightNumber
     *
     * @param string $flightNumber
     * @return HighSpeedFuel
     */
    public function setFlightNumber($flightNumber)
    {
        $this->flightNumber = $flightNumber;

        return $this;
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
     * Set depIATA
     *
     * @param string $depIATA
     * @return HighSpeedFuel
     */
    public function setDepIATA($depIATA)
    {
        $this->depIATA = $depIATA;

        return $this;
    }

    /**
     * Get depIATA
     *
     * @return string 
     */
    public function getDepIATA()
    {
        return $this->depIATA;
    }

    /**
     * Set destIATA
     *
     * @param string $destIATA
     * @return HighSpeedFuel
     */
    public function setDestIATA($destIATA)
    {
        $this->destIATA = $destIATA;

        return $this;
    }

    /**
     * Get destIATA
     *
     * @return string 
     */
    public function getDestIATA()
    {
        return $this->destIATA;
    }

    /**
     * Set normalFlightTime
     *
     * @param \DateTime $normalFlightTime
     * @return HighSpeedFuel
     */
    public function setNormalFlightTime($normalFlightTime)
    {
        $this->normalFlightTime = $normalFlightTime;

        return $this;
    }

    /**
     * Get normalFlightTime
     *
     * @return \DateTime 
     */
    public function getNormalFlightTime()
    {
        return $this->normalFlightTime;
    }

    /**
     * Set highSpeedFlightTime
     *
     * @param \DateTime $highSpeedFlightTime
     * @return HighSpeedFuel
     */
    public function setHighSpeedFlightTime($highSpeedFlightTime)
    {
        $this->highSpeedFlightTime = $highSpeedFlightTime;

        return $this;
    }

    /**
     * Get highSpeedFlightTime
     *
     * @return \DateTime 
     */
    public function getHighSpeedFlightTime()
    {
        return $this->highSpeedFlightTime;
    }

    /**
     * Set normalCost
     *
     * @param integer $normalCost
     * @return HighSpeedFuel
     */
    public function setNormalCost($normalCost)
    {
        $this->normalCost = $normalCost;

        return $this;
    }

    /**
     * Get normalCost
     *
     * @return integer 
     */
    public function getNormalCost()
    {
        return $this->normalCost;
    }

    /**
     * Set highSpeedCost
     *
     * @param integer $highSpeedCost
     * @return HighSpeedFuel
     */
    public function setHighSpeedCost($highSpeedCost)
    {
        $this->highSpeedCost = $highSpeedCost;

        return $this;
    }

    /**
     * Get highSpeedCost
     *
     * @return integer 
     */
    public function getHighSpeedCost()
    {
        return $this->highSpeedCost;
    }

    /**
     * Set archived
     *
     * @param boolean $archived
     * @return HighSpeedFuel
     */
    public function setArchived($archived)
    {
        $this->archived = $archived;

        return $this;
    }

    /**
     * Get archived
     *
     * @return boolean 
     */
    public function getArchived()
    {
        return $this->archived;
    }
}
