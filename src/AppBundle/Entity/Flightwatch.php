<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Flightwatch
 */
class Flightwatch
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

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->info = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set flightNumber
     *
     * @param string $flightNumber
     * @return Flightwatch
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
     * Set dep
     *
     * @param string $dep
     * @return Flightwatch
     */
    public function setDep($dep)
    {
        $this->dep = $dep;

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
     * Set dest
     *
     * @param string $dest
     * @return Flightwatch
     */
    public function setDest($dest)
    {
        $this->dest = $dest;

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
     * Set flightDate
     *
     * @param \DateTime $flightDate
     * @return Flightwatch
     */
    public function setFlightDate($flightDate)
    {
        $this->flightDate = $flightDate;

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
     * Set takeOffTime
     *
     * @param \DateTime $takeOffTime
     * @return Flightwatch
     */
    public function setTakeOffTime($takeOffTime)
    {
        $this->takeOffTime = $takeOffTime;

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
     * Add info
     *
     * @param \AppBundle\Entity\FlightwatchInfo $info
     * @return Flightwatch
     */
    public function addInfo(\AppBundle\Entity\FlightwatchInfo $info)
    {
        $this->info[] = $info;

        return $this;
    }

    /**
     * Remove info
     *
     * @param \AppBundle\Entity\FlightwatchInfo $info
     */
    public function removeInfo(\AppBundle\Entity\FlightwatchInfo $info)
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
     * @var \DateTime
     */
    private $std;


    /**
     * Set std
     *
     * @param \DateTime $std
     * @return Flightwatch
     */
    public function setStd($std)
    {
        $this->std = $std;

        return $this;
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
     * Set altn
     *
     * @param string $altn
     * @return Flightwatch
     */
    public function setAltn($altn)
    {
        $this->altn = $altn;

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
     * Set erd
     *
     * @param string $erd
     * @return Flightwatch
     */
    public function setErd($erd)
    {
        $this->erd = $erd;

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
     * Set erda
     *
     * @param string $erda
     * @return Flightwatch
     */
    public function setErda($erda)
    {
        $this->erda = $erda;

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
     * @var boolean
     */
    private $completed;


    /**
     * Set completed
     *
     * @param boolean $completed
     * @return Flightwatch
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
}
