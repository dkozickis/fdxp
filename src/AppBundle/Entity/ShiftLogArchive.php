<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ShiftLogArchive
 */
class ShiftLogArchive
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
     * @var \DateTime
     */
    private $archivedDate;

    /**
     * @var string
     */
    private $archivedShift;

    /**
     * @var string
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
     * @return ShiftLogArchive
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
     * @return ShiftLogArchive
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
     * Set archivedDate
     *
     * @param \DateTime $archivedDate
     * @return ShiftLogArchive
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
     * @return ShiftLogArchive
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
     * @param string $archivedBy
     * @return ShiftLogArchive
     */
    public function setArchivedBy($archivedBy)
    {
        $this->archivedBy = $archivedBy;

        return $this;
    }

    /**
     * Get archivedBy
     *
     * @return string 
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
     * @var integer
     */
    private $order;


    /**
     * Set infoHeader
     *
     * @param string $infoHeader
     * @return ShiftLogArchive
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
     * Set order
     *
     * @param integer $order
     * @return ShiftLogArchive
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
     * @return ShiftLogArchive
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
