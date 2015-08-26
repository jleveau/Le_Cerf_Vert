<?php

namespace Application\Sonata\UserBundle\Entity;
// <dest-path>/Application/Sonata/UserBundle/Entity/User.php

use Sonata\UserBundle\Entity\BaseUser as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Application\Sonata\UserBundle\Entity\UserRepository")
 * @ORM\Table(name="fos_user_user")
 */
class User extends BaseUser {
    /**
     * @var integer $id
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="LCV\PlatformBundle\Entity\Article", mappedBy="author")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $articles;
    
        /**
     * @ORM\OneToMany(targetEntity="LCV\CommentBundle\Entity\Comment", mappedBy="author", cascade={"remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    protected $comments;
    
     /**
     * 
     * @ORM\OneToMany(targetEntity="LCV\CoreBundle\Entity\Notification", mappedBy="user",cascade={"remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    protected $notifications;
    
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $groups;
    
    /**
     * @ORM\OneToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media", cascade={"remove","persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    protected $avatar;
    
     /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_activity", type="datetime")
     */
    protected $lastActivity;
    
    /**
     * @ORM\OneToOne(targetEntity="Invitation")
     * @ORM\JoinColumn(referencedColumnName="code")
     * @Assert\NotNull(message="Your invitation is wrong", groups={"Registration"})
     */ 
    protected $invitation;
    
     /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
        $this -> articles = new \Doctrine\Common\Collections\ArrayCollection();
        $this -> groups = new \Doctrine\Common\Collections\ArrayCollection();
        $this -> comments = new \Doctrine\Common\Collections\ArrayCollection();
        $this -> notifications = new \Doctrine\Common\Collections\ArrayCollection();
        $this -> lastActivity = new \DateTime();
        
    }
    
   public function isActiveNow(){
       $this->setLastActivity(new \DateTime());
   }

    /**
     * Get id
     *
     * @return integer $id
     */
    public function getId() {
        return $this -> id;
    }

    /**
     * Add groups
     *
     * @param \Application\Sonata\UserBundle\Entity\Group $groups
     * @return User
     */
    public function addGroup(\FOS\UserBundle\Model\GroupInterface $groups) {
        $this -> groups[] = $groups;

        return $this;
    }

    /**
     * Remove groups
     *
     * @param \Application\Sonata\UserBundle\Entity\Group $groups
     */
    public function removeGroup(\FOS\UserBundle\Model\GroupInterface $groups) {
        $this -> groups -> removeElement($groups);
    }

    /**
     * Get groups
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGroups() {
        return $this -> groups;
    }

    public function addArticle(\LCV\PlatformBundle\Entity\Article $article) {
        $this -> articles[] = $article;

        return $this;
    }

    public function removeArticle(\LCV\PlatformBundle\Entity\Article $article) {
        $this -> articles -> removeElement($article);
    }

    public function getArticles() {
        return $this -> articles;
    }
    
    public function addComment(\LCV\CommentBundle\Entity\Comment $comment) {
        $this -> comments[] = $comment;
        $comment->setAuthor($this);
        return $this;
    }

    public function removeComment(\LCV\CommentBundle\Entity\Comment $comment) {
        $this -> comments -> removeElement($comment);
    }

    public function getComment() {
        return $this -> comments;
    }


    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set avatar
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $avatar
     * @return User
     */
    public function setAvatar(\Application\Sonata\MediaBundle\Entity\Media $avatar = null)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return \Application\Sonata\MediaBundle\Entity\Media 
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Add notifications
     *
     * @param \LCV\CoreBundle\Entity\Comment $notifications
     * @return User
     */
    public function addNotification(\LCV\CoreBundle\Entity\Notification $notifications)
    {
        $this->notifications[] = $notifications;
        $notifications->setUser($this);
        return $this;
    }

    /**
     * Remove notifications
     *
     * @param \LCV\CoreBundle\Entity\Comment $notifications
     */
    public function removeNotification(\LCV\CoreBundle\Entity\Notification $notifications)
    {
        $this->notifications->removeElement($notifications);
    }

    /**
     * Get notifications
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getNotifications()
    {
        return $this->notifications;
    }

    /**
     * Set lastActivity
     *
     * @param \DateTime $lastActivity
     * @return User
     */
    public function setLastActivity($lastActivity)
    {
        $this->lastActivity = $lastActivity;

        return $this;
    }

    /**
     * Get lastActivity
     *
     * @return \DateTime 
     */
    public function getLastActivity()
    {
        return $this->lastActivity;
    }
    
    public function setInvitation(Invitation $invitation)
    {
        $this->invitation = $invitation;
    }

    public function getInvitation()
    {
        return $this->invitation;
    }
}
