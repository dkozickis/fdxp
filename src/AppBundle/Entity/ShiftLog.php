<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ShiftLog
 */
class ShiftLog
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $infoType;

    /**
     * @var string
     */
    private $content;

    /**
     * @var boolean
     */
    private $current;

    /**
     * @var \DateTime
     */
    private $archivedDate;

    /**
     * @var string
     */
    private $archivedShift;

    /**
     * @var integer
     */
    private $archivedBy;


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
     * Set infoType
     *
     * @param string $infoType
     * @return ShiftLog
     */
    public function setInfoType($infoType)
    {
        $this->infoType = $infoType;

        return $this;
    }

    /**
     * Get infoType
     *
     * @return string 
     */
    public function getInfoType()
    {
        return $this->infoType;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return ShiftLog
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set current
     *
     * @param boolean $current
     * @return ShiftLog
     */
    public function setCurrent($current)
    {
        $this->current = $current;

        return $this;
    }

    /**
     * Get current
     *
     * @return boolean 
     */
    public function getCurrent()
    {
        return $this->current;
    }

    /**
     * Set archivedDate
     *
     * @param \DateTime $archivedDate
     * @return ShiftLog
     */
    public function setArchivedDate($archivedDate)
    {
        $this->archivedDate = $archivedDate;

        return $this;
    }

    /**
     * Get archivedDate
     *
     * @return \DateTime 
     */
    public function getArchivedDate()
    {
        return $this->archivedDate;
    }

    /**
     * Set archivedShift
     *
     * @param string $archivedShift
     * @return ShiftLog
     */
    public function setArchivedShift($archivedShift)
    {
        $this->archivedShift = $archivedShift;

        return $this;
    }

    /**
     * Get archivedShift
     *
     * @return string 
     */
    public function getArchivedShift()
    {
        return $this->archivedShift;
    }

    /**
     * Set archivedBy
     *
     * @param integer $archivedBy
     * @return ShiftLog
     */
    public function setArchivedBy($archivedBy)
    {
        $this->archivedBy = $archivedBy;

        return $this;
    }

    /**
     * Get archivedBy
     *
     * @return integer 
     */
    public function getArchivedBy()
    {
        return $this->archivedBy;
    }
    /**
     * @var string
     */
    private $infoHeader;


    /**
     * Set infoHeader
     *
     * @param string $infoHeader
     * @return ShiftLog
     */
    public function setInfoHeader($infoHeader)
    {
        $this->infoHeader = $infoHeader;

        return $this;
    }

    /**
     * Get infoHeader
     *
     * @return string 
     */
    public function getInfoHeader()
    {
        return $this->infoHeader;
    }
    /**
     * @var integer
     */
    private $order;


    /**
     * Set order
     *
     * @param integer $order
     * @return ShiftLog
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return integer 
     */
    public function getOrder()
    {
        return $this->order;
    }
    /**
     * @var integer
     */
    private $sequence;


    /**
     * Set sequence
     *
     * @param integer $sequence
     * @return ShiftLog
     */
    public function setSequence($sequence)
    {
        $this->sequence = $sequence;

        return $this;
    }

    /**
     * Get sequence
     *
     * @return integer 
     */
    public function getSequence()
    {
        return $this->sequence;
    }
}
