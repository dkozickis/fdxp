<?php

namespace AppBundle\Entity;

/**
 * ShiftLog.
 */
class ShiftLog
{
    /**
     * @var int
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
     * @var bool
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
     * @var int
     */
    private $archivedBy;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set infoType.
     *
     * @param string $infoType
     *
     * @return ShiftLog
     */
    public function setInfoType($infoType)
    {
        $this->infoType = $infoType;

        return $this;
    }

    /**
     * Get infoType.
     *
     * @return string
     */
    public function getInfoType()
    {
        return $this->infoType;
    }

    /**
     * Set content.
     *
     * @param string $content
     *
     * @return ShiftLog
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content.
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set current.
     *
     * @param bool $current
     *
     * @return ShiftLog
     */
    public function setCurrent($current)
    {
        $this->current = $current;

        return $this;
    }

    /**
     * Get current.
     *
     * @return bool
     */
    public function getCurrent()
    {
        return $this->current;
    }

    /**
     * Set archivedDate.
     *
     * @param \DateTime $archivedDate
     *
     * @return ShiftLog
     */
    public function setArchivedDate($archivedDate)
    {
        $this->archivedDate = $archivedDate;

        return $this;
    }

    /**
     * Get archivedDate.
     *
     * @return \DateTime
     */
    public function getArchivedDate()
    {
        return $this->archivedDate;
    }

    /**
     * Set archivedShift.
     *
     * @param string $archivedShift
     *
     * @return ShiftLog
     */
    public function setArchivedShift($archivedShift)
    {
        $this->archivedShift = $archivedShift;

        return $this;
    }

    /**
     * Get archivedShift.
     *
     * @return string
     */
    public function getArchivedShift()
    {
        return $this->archivedShift;
    }

    /**
     * Set archivedBy.
     *
     * @param int $archivedBy
     *
     * @return ShiftLog
     */
    public function setArchivedBy($archivedBy)
    {
        $this->archivedBy = $archivedBy;

        return $this;
    }

    /**
     * Get archivedBy.
     *
     * @return int
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
     * Set infoHeader.
     *
     * @param string $infoHeader
     *
     * @return ShiftLog
     */
    public function setInfoHeader($infoHeader)
    {
        $this->infoHeader = $infoHeader;

        return $this;
    }

    /**
     * Get infoHeader.
     *
     * @return string
     */
    public function getInfoHeader()
    {
        return $this->infoHeader;
    }
    /**
     * @var int
     */
    private $order;

    /**
     * Set order.
     *
     * @param int $order
     *
     * @return ShiftLog
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order.
     *
     * @return int
     */
    public function getOrder()
    {
        return $this->order;
    }
    /**
     * @var int
     */
    private $sequence;

    /**
     * Set sequence.
     *
     * @param int $sequence
     *
     * @return ShiftLog
     */
    public function setSequence($sequence)
    {
        $this->sequence = $sequence;

        return $this;
    }

    /**
     * Get sequence.
     *
     * @return int
     */
    public function getSequence()
    {
        return $this->sequence;
    }
}
