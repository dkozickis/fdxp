<?php

namespace AppBundle\Entity;

use AppBundle\Entity\ComparisonCase;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Comparison.
 */
class Comparison
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

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
     * Set name.
     *
     * @param string $name
     *
     * @return Comparison
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $cases;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->cases = new ArrayCollection();
    }

    /**
     * Add cases.
     *
     * @param ComparisonCase $cases
     *
     * @return Comparison
     */
    public function addCase(ComparisonCase $cases)
    {
        $this->cases[] = $cases;

        return $this;
    }

    /**
     * Remove cases.
     *
     * @param ComparisonCase $cases
     */
    public function removeCase(ComparisonCase $cases)
    {
        $this->cases->removeElement($cases);
    }

    /**
     * Get cases.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCases()
    {
        return $this->cases;
    }

    public function __toString()
    {
        return $this->name;
    }
}
