<?php

namespace AppBundle\Entity;

use AppBundle\Entity\ComparisonCase;

/**
 * ComparisonCaseCalc.
 */
class ComparisonCaseCalc
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $citypair;

    /**
     * @var int
     */
    private $cost;

    /**
     * @var \DateTime
     */
    private $time;

    /**
     * @var ComparisonCase
     */
    private $case;

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
     * Set citypair.
     *
     * @param string $citypair
     *
     * @return ComparisonCaseCalc
     */
    public function setCitypair($citypair)
    {
        $this->citypair = $citypair;

        return $this;
    }

    /**
     * Get citypair.
     *
     * @return string
     */
    public function getCitypair()
    {
        return $this->citypair;
    }

    /**
     * Set cost.
     *
     * @param int $cost
     *
     * @return ComparisonCaseCalc
     */
    public function setCost($cost)
    {
        $this->cost = $cost;

        return $this;
    }

    /**
     * Get cost.
     *
     * @return int
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * Set time.
     *
     * @param \DateTime $time
     *
     * @return ComparisonCaseCalc
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get time.
     *
     * @return \DateTime
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set case.
     *
     * @param ComparisonCase $case
     *
     * @return ComparisonCaseCalc
     */
    public function setCase(ComparisonCase $case = null)
    {
        $this->case = $case;

        return $this;
    }

    /**
     * Get case.
     *
     * @return ComparisonCase
     */
    public function getCase()
    {
        return $this->case;
    }
    /**
     * @var string
     */
    private $route;


    /**
     * Set route
     *
     * @param string $route
     * @return ComparisonCaseCalc
     */
    public function setRoute($route)
    {
        $this->route = $route;

        return $this;
    }

    /**
     * Get route
     *
     * @return string 
     */
    public function getRoute()
    {
        return $this->route;
    }
}
