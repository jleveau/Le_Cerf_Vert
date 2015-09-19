<?php

namespace LCV\PlaylistBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * AudioCategory
 * @Gedmo\Tree(type="nested")
 * @ORM\Table(name="lcv_playlist_audio_category")
 * @ORM\Entity(repositoryClass="LCV\PlaylistBundle\Entity\AudioCategoryRepository")
 * @UniqueEntity("name")
 * 
 */
class AudioCategory
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

     /**
     * @ORM\Column(name="ramovable", type="boolean")
     */
    private $removable;

    /**
     * @ORM\OneToMany(targetEntity="LCV\PlaylistBundle\Entity\AudioFile", mappedBy="category")
     */
    private $audios;
    
    /**
     * @Gedmo\TreeLeft
     * @ORM\Column(name="lft", type="integer")
     */
    private $lft;

    /**
     * @Gedmo\TreeLevel
     * @ORM\Column(name="lvl", type="integer")
     */
    private $lvl;

    /**
     * @Gedmo\TreeRight
     * @ORM\Column(name="rgt", type="integer")
     */
    private $rgt;

    /**
     * @Gedmo\TreeRoot
     * @ORM\Column(name="root", type="integer", nullable=true)
     */
    private $root;

    /**
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="AudioCategory", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="AudioCategory", mappedBy="parent")
     * @ORM\OrderBy({"lft" = "ASC"})
     */
    private $children;
    
     /**
     * Constructor
     */
    public function __construct()
    {
        $this->audios = new \Doctrine\Common\Collections\ArrayCollection();
        $this->removable=true;
    }

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
     * @return AudioCategory
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
     * Set level
     *
     * @param integer $level
     * @return AudioCategory
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return integer 
     */
    public function getLevel()
    {
        return $this->level;
    }

    

    /**
     * Set removable
     *
     * @param boolean $removable
     * @return AudioCategory
     */
    public function setRemovable($removable)
    {
        $this->removable = $removable;

        return $this;
    }

    /**
     * Get removable
     *
     * @return boolean 
     */
    public function getRemovable()
    {
        return $this->removable;
    }

    /**
     * Add audios
     *
     * @param \LCV\PlaylistBundle\Entity\AudioFile $audios
     * @return AudioCategory
     */
    public function addAudio(\LCV\PlaylistBundle\Entity\AudioFile $audios)
    {
        $this->audios[] = $audios;

        return $this;
    }

    /**
     * Remove audios
     *
     * @param \LCV\PlaylistBundle\Entity\AudioFile $audios
     */
    public function removeAudio(\LCV\PlaylistBundle\Entity\AudioFile $audios)
    {
        $this->audios->removeElement($audios);
    }

    /**
     * Get audios
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAudios()
    {
        return $this->audios;
    }

    public function setParent(AudioCategory $parent = null)
    {
        $this->parent = $parent;
    }

    public function getParent()
    {
        return $this->parent;
    }
  
}
