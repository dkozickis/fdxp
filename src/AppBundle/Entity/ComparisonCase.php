<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ComparisonCase
 */
class ComparisonCase
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var integer
     */
    private $basic;


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
     * Set name
     *
     * @param string $name
     * @return ComparisonCase
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set basic
     *
     * @param integer $basic
     * @return ComparisonCase
     */
    public function setBasic($basic)
    {
        $this->basic = $basic;

        return $this;
    }

    /**
     * Get basic
     *
     * @return integer 
     */
    public function getBasic()
    {
        return $this->basic;
    }
    /**
     * @var \AppBundle\Entity\Comparison
     */
    private $comparison;


    /**
     * Set comparison
     *
     * @param \AppBundle\Entity\Comparison $comparison
     * @return ComparisonCase
     */
    public function setComparison(\AppBundle\Entity\Comparison $comparison = null)
    {
        $this->comparison = $comparison;

        return $this;
    }

    /**
     * Get comparison
     *
     * @return \AppBundle\Entity\Comparison 
     */
    public function getComparison()
    {
        return $this->comparison;
    }
}
