<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Waypoints
 */
class Waypoints
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $wpt_id;

    /**
     * @var float
     */
    private $lat;

    /**
     * @var float
     */
    private $lon;


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
     * Set wpt_id
     *
     * @param string $wptId
     * @return Waypoints
     */
    public function setWptId($wptId)
    {
        $this->wpt_id = $wptId;

        return $this;
    }

    /**
     * Get wpt_id
     *
     * @return string 
     */
    public function getWptId()
    {
        return $this->wpt_id;
    }

    /**
     * Set lat
     *
     * @param float $lat
     * @return Waypoints
     */
    public function setLat($lat)
    {
        $this->lat = $lat;

        return $this;
    }

    /**
     * Get lat
     *
     * @return float 
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Set lon
     *
     * @param float $lon
     * @return Waypoints
     */
    public function setLon($lon)
    {
        $this->lon = $lon;

        return $this;
    }

    /**
     * Get lon
     *
     * @return float 
     */
    public function getLon()
    {
        return $this->lon;
    }
}
