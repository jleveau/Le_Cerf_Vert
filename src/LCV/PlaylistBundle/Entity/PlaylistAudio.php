<?php

namespace LCV\PlaylistBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * PlaylistAudio
 *
 * @ORM\Table(name="lcv_playlistaudio")
 * @ORM\Entity
 */
class PlaylistAudio
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
     * @ORM\ManyToOne(targetEntity="LCV\PlaylistBundle\Entity\Playlist", inversedBy="playlist_audios", cascade={"persist"})
     */
    private $playlist;
    
     /**
     * 
     *@Assert\Valid()
     * @ORM\ManyToOne(targetEntity="LCV\PlaylistBundle\Entity\AudioFile", inversedBy="playlist_audios", cascade={"persist"})
     */
    private $audio;

    /**
     * @var integer
     *
     * @ORM\Column(name="sortOrder", type="integer")
     */
    private $sortOrder;

     /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255,nullable=false)
     * @Assert\NotBlank()
     */
    private $title; 

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
     * Set sortOrder
     *
     * @param integer $sortOrder
     * @return PlaylistAudio
     */
    public function setSortOrder($sortOrder)
    {
        $this->sortOrder = $sortOrder;

        return $this;
    }

    /**
     * Get sortOrder
     *
     * @return integer 
     */
    public function getSortOrder()
    {
        return $this->sortOrder;
    }

    /**
     * Set playlist
     *
     * @param \LCV\PlaylistBundle\Entity\Playlist $playlist
     * @return PlaylistAudio
     */
    public function setPlaylist(\LCV\PlaylistBundle\Entity\Playlist $playlist = null)
    {
        $this->playlist = $playlist;

        return $this;
    }

    /**
     * Get playlist
     *
     * @return \LCV\PlaylistBundle\Entity\Playlist 
     */
    public function getPlaylist()
    {
        return $this->playlist;
    }

    /**
     * Set audio
     *
     * @param \LCV\PlaylistBundle\Entity\AudioFile $audio
     * @return PlaylistAudio
     */
    public function setAudio(\LCV\PlaylistBundle\Entity\AudioFile $audio = null)
    {
        $this->audio = $audio;

        return $this;
    }

    /**
     * Get audio
     *
     * @return \LCV\PlaylistBundle\Entity\AudioFile 
     */
    public function getAudio()
    {
        return $this->audio;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return PlaylistAudio
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }
}
