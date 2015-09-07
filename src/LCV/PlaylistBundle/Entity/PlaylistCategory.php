<?php

namespace LCV\PlaylistBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * PlaylistCategory
 *
 * @ORM\Table(name="lcv_playlist_category")
 * @ORM\Entity(repositoryClass="LCV\PlaylistBundle\Entity\PlaylistCategoryRepository")
 * @UniqueEntity("name")
 * 
 */
class PlaylistCategory
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
     * @ORM\OneToMany(targetEntity="LCV\PlaylistBundle\Entity\Playlist", mappedBy="category")
     */
    private $playlists;

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
     * @return PlaylistCategory
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
     * Constructor
     */
    public function __construct()
    {
        $this->playlists = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add playlists
     *
     * @param \LCV\PlaylistBundle\Entity\Playlist $playlists
     * @return PlaylistCategory
     */
    public function addPlaylist(\LCV\PlaylistBundle\Entity\Playlist $playlists)
    {
        $this->playlists[] = $playlists;

        return $this;
    }

    /**
     * Remove playlists
     *
     * @param \LCV\PlaylistBundle\Entity\Playlist $playlists
     */
    public function removePlaylist(\LCV\PlaylistBundle\Entity\Playlist $playlists)
    {
        $this->playlists->removeElement($playlists);
    }

    /**
     * Get playlists
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPlaylists()
    {
        return $this->playlists;
    }
}
