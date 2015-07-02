<?php

namespace AppBundle\Entity;

/**
 * ShiftLogOnShift
 */
class ShiftLogOnShift
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $shiftDate;

    /**
     * @var string
     */
    private $shiftPeriod;

    /**
     * @var array
     */
    private $onShift;


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
     * Set shiftDate
     *
     * @param \DateTime $shiftDate
     * @return ShiftLogOnShift
     */
    public function setShiftDate($shiftDate)
    {
        $this->shiftDate = $shiftDate;

        return $this;
    }

    /**
     * Get shiftDate
     *
     * @return \DateTime 
     */
    public function getShiftDate()
    {
        return $this->shiftDate;
    }

    /**
     * Set shiftPeriod
     *
     * @param string $shiftPeriod
     * @return ShiftLogOnShift
     */
    public function setShiftPeriod($shiftPeriod)
    {
        $this->shiftPeriod = $shiftPeriod;

        return $this;
    }

    /**
     * Get shiftPeriod
     *
     * @return string 
     */
    public function getShiftPeriod()
    {
        return $this->shiftPeriod;
    }

    /**
     * Set onShift
     *
     * @param array $onShift
     * @return ShiftLogOnShift
     */
    public function setOnShift($onShift)
    {
        $this->onShift = $onShift;

        return $this;
    }

    /**
     * Get onShift
     *
     * @return array 
     */
    public function getOnShift()
    {
        return $this->onShift;
    }
}
