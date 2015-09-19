<?php

namespace LCV\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Notification
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="LCV\CoreBundle\Entity\NotificationRepository")
 * @ORM\HasLifeCycleCallbacks()
 * 
 */
class Notification
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
     * @ORM\Column(name="message", type="text")
     */
    private $message;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var Application\Sonata\UserBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User", inversedBy="notifications")
     */
    private $user;
    
     /**
     * @var string
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;
    
     /**
     * @var LCV\PlaylistBundle\Entity\Playlist
     *
     * @ORM\ManyToOne(targetEntity="LCV\PlaylistBundle\Entity\Playlist", inversedBy="notifications")
     */
    private $playlist;
    
     /**
     * @var LCV\PlatformBundle\Entity\Article
     *
     * @ORM\ManyToOne(targetEntity="LCV\PlatformBundle\Entity\Article", inversedBy="notifications")
     */
    private $article;
    

    public function __construct() {
        $this -> date = new \DateTime();
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
     * Set message
     *
     * @param string $message
     * @return Notification
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string 
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Notification
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set user
     *
     * @param \stdClass $user
     * @return Notification
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \stdClass 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Notification
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set article
     *
     * @param \LCV\PlatformBundle\Entity\Article $article
     * @return Notification
     * 
     */
    public function setArticle(\LCV\PlatformBundle\Entity\Article $article = null)
    {
        $this->article = $article;

        return $this;
    }

    /**
     * Get article
     *
     * @return \LCV\PlatformBundle\Entity\Article 
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * Set playlist
     *
     * @param \LCV\PlaylistBundle\Entity\Playlist $playlist
     * @return Notification
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
}
