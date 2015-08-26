<?php
// src/MyProject/MyBundle/Entity/Vote.php

namespace LCV\CoreBundle\Entity;

use DCS\RatingBundle\Entity\Vote as BaseVote;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Vote
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * @ORM\ManyToOne(targetEntity="LCV\CoreBundle\Entity\Rating", inversedBy="votes")
     * @ORM\JoinColumn(name="rating_id", referencedColumnName="id")
     */
    private $rating;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User")
     */
    private $voter;
    
    /**
     * @var integer
     * @ORM\Column(name="value", type="integer",nullable=true)
     */
    private $value;

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
     * Set value
     *
     * @param integer $value
     * @return Vote
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return integer 
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set rating
     *
     * @param \LCV\CoreBundle\Entity\Rating $rating
     * @return Vote
     */
    public function setRating(\LCV\CoreBundle\Entity\Rating $rating = null)
    {
        $this->rating = $rating;
        return $this;
    }

    /**
     * Get rating
     *
     * @return \LCV\CoreBundle\Entity\Rating 
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Set voter
     *
     * @param \Application\Sonata\UserBundle\Entity\User $voter
     * @return Vote
     */
    public function setVoter(\Application\Sonata\UserBundle\Entity\User $voter = null)
    {
        $this->voter = $voter;

        return $this;
    }

    /**
     * Get voter
     *
     * @return \Application\Sonata\UserBundle\Entity\User 
     */
    public function getVoter()
    {
        return $this->voter;
    }
}
