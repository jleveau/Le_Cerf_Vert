<?php

/**
 * This file is part of the <name> project.
 *
 * (c) <yourname> <youremail>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\UserBundle\Entity;
 
use Sonata\UserBundle\Entity\BaseUser as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 *   
 * @ORM\Entity
 * @ORM\Table(name="lcv_user")
 */
class User extends BaseUser
{
    /**
     * @var integer $id
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Get id
     *
     * @return integer $id
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Constructor
     */
    public function __construct()
    {
    	parent::__construct();
    	$this->articles = new \Doctrine\Common\Collections\ArrayCollection();
    	$this->groups = new \Doctrine\Common\Collections\ArrayCollection();
    }

    
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $groups;
    
    
    /**
     * Add groups
     *
     * @param \Application\Sonata\UserBundle\Entity\Group $groups
     * @return User
     */
    public function addGroup(\FOS\UserBundle\Model\GroupInterface $groups)
    {
    	$this->groups[] = $groups;
    
    	return $this;
    }
    
    /**
     * Remove groups
     *
     * @param \Application\Sonata\UserBundle\Entity\Group $groups
     */
    public function removeGroup(\FOS\UserBundle\Model\GroupInterface $groups)
    {
    	$this->groups->removeElement($groups);
    }
    
    /**
     * Get groups
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGroups()
    {
    	return $this->groups;
    }
    
    
}
