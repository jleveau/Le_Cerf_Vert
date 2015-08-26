<?php
// src/MyProject/MyBundle/Entity/Rating.php

namespace LCV\CoreBundle\Entity;

use DCS\RatingBundle\Entity\Rating as BaseRating;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Rating
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
     * @ORM\OneToMany(targetEntity="LCV\CoreBundle\Entity\Vote", mappedBy="rating", cascade={"persist", "remove"})
     */
    private $votes;
    
     /**
     * @ORM\Column(name="rate", type="integer", unique=false,nullable=true)
     */
    private $rate;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->votes = new \Doctrine\Common\Collections\ArrayCollection();
    }
    public function __toString() {
        return strval($this -> rate);
    }
    
    /**
     * Set id
     *
     * @param string $id
     * @return Rating
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id
     *
     * @return string 
     */
    public function getId()
    {
        return $this->id;
    }

    

    /**
     * Add votes
     *
     * @param \LCV\CoreBundle\Entity\Vote $votes
     * @return Rating
     */
    public function addVote(\LCV\CoreBundle\Entity\Vote $votes)
    {
        $this->votes[] = $votes;

        return $this;
    }

    /**
     * Remove votes
     *
     * @param \LCV\CoreBundle\Entity\Vote $votes
     */
    public function removeVote(\LCV\CoreBundle\Entity\Vote $votes)
    {
        $this->votes->removeElement($votes);
    }

    /**
     * Get votes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVotes()
    {
        return $this->votes;
    }

    /**
     * Set rate
     *
     * @param integer $rate
     * @return Rating
     */
    public function setRate($rate)
    {
        $this->rate = $rate;

        return $this;
    }

    /**
     * Get rate
     *
     * @return integer 
     */
    public function getRate()
    {
        $sum=0;
        if ($this->votes->count()==0)
            return 0;
        foreach($this->votes as $vote)
        {
                $sum+=$vote->getValue();
         }
           $this->rate=$sum/$this->votes->count();
        return $this->rate;
    }
        
}
