<?php

namespace AppBundle\Entity;

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
        $this->cases = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add cases.
     *
     * @param \AppBundle\Entity\ComparisonCase $cases
     *
     * @return Comparison
     */
    public function addCase(\AppBundle\Entity\ComparisonCase $cases)
    {
        $this->cases[] = $cases;

        return $this;
    }

    /**
     * Remove cases.
     *
     * @param \AppBundle\Entity\ComparisonCase $cases
     */
    public function removeCase(\AppBundle\Entity\ComparisonCase $cases)
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
