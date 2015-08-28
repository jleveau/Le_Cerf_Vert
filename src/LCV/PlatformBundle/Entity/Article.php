<?php

namespace LCV\PlatformBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use LCV\CoreBundle\Entity\Rating;


/**
 * Article
 *
 * @ORM\Table(name="lcv_article")
 * @ORM\Entity(repositoryClass="LCV\PlatformBundle\Entity\ArticleRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity("title")
 */
class Article {

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
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var Application\Sonata\UserBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User", inversedBy="articles" )
     */
    private $author;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="LCV\PlatformBundle\Entity\Category", inversedBy="articles")
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity="LCV\CommentBundle\Entity\Comment", mappedBy="article", cascade={"remove"})
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity="LCV\CoreBundle\Entity\Notification", mappedBy="article", cascade={"remove"})
     */
    private $notifications;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     * @Assert\NotBlank()
     */
    private $content;

    /**
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(length=128, unique=true)
     */
    private $slug;

    /**
     * @ORM\OneToOne(targetEntity="LCV\CoreBundle\Entity\Rating", cascade={"persist","remove"})
     */
    private $rate;
    
    /**
     * @var integer
     * @ORM\Column(name="view", type="integer",options={"default" = 0})
     */
    private $view;
    
     /**
     * @ORM\OneToOne(targetEntity="LCV\CoreBundle\Entity\News", mappedBy="article", orphanRemoval=true)
     **/
    private $new;
    
    ////////////
    /**
     * @ORM\PreUpdate
     */
    public function updateDate() {
        $this -> setUpdatedAt(new \DateTime());
    }

    public function incViews() {
        $this -> setView($this->getView()+1);
    }

    ///////////

    public function __construct() {
        $this -> date = new \DateTime();
        $this -> updatedAt = $this -> date;
        $this->view=0;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this -> id;
    }

    /**
     * Set id
     *
     * @param integer
     * @return Article
     */
    public function setId($id) {
        $this -> id = $id;
        return $this;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Article
     */
    public function setContent($content) {
        $this -> content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent() {
        return $this -> content;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Article
     */
    public function setSlug($slug) {
        $this -> slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug() {
        return $this -> slug;
    }

    /**
     * Set category
     *
     * @param \LCV\PlatformBundle\Entity\Article $category
     * @return Article
     */
    public function setCategory(\LCV\PlatformBundle\Entity\Category $category = null) {
        $this -> category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \LCV\PlatformBundle\Entity\Category
     */
    public function getCategory() {
        return $this -> category;
    }

    public function __toString() {
        return $this -> title;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Article
     */
    public function setTitle($title) {
        $this -> title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle() {
        return $this -> title;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Article
     */
    public function setDate($date) {
        $this -> date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate() {
        return $this -> date;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Article
     */
    public function setUpdatedAt($updatedAt) {
        $this -> updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt() {
        return $this -> updatedAt;
    }

    /**
     * Set author
     *
     * @param \Application\Sonata\UserBundle\Entity\User $author
     * @return Article
     */
    public function setAuthor(\Application\Sonata\UserBundle\Entity\User $author = null) {
        $this -> author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return \Application\Sonata\UserBundle\Entity\User
     */
    public function getAuthor() {
        return $this -> author;
    }

    /**
     * Add comments
     *
     * @param \LCV\CommentBundle\Entity\Comment $comments
     * @return Article
     */
    public function addComment(\LCV\CommentBundle\Entity\Comment $comments) {
        $this -> comments[] = $comments;
        $comments -> setArticle($this);

        return $this;
    }

    /**
     * Remove comments
     *
     * @param \LCV\CommentBundle\Entity\Comment $comments
     */
    public function removeComment(\LCV\CommentBundle\Entity\Comment $comments) {
        $this -> comments -> removeElement($comments);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComments() {
        return $this -> comments;
    }

    /**
     * Add notifications
     *
     * @param \LCV\CoreBundle\Entity\Notification $notifications
     * @return Article
     */
    public function addNotification(\LCV\CoreBundle\Entity\Notification $notifications) {
        $this -> notifications[] = $notifications;

        return $this;
    }

    /**
     * Remove notifications
     *
     * @param \LCV\CoreBundle\Entity\Notification $notifications
     */
    public function removeNotification(\LCV\CoreBundle\Entity\Notification $notifications) {
        $this -> notifications -> removeElement($notifications);
    }

    /**
     * Get notifications
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNotifications() {
        return $this -> notifications;
    }

    /**
     * Set view
     *
     * @param integer $view
     * @return Article
     */
    public function setView($view) {
        $this -> view = $view;

        return $this;
    }

    /**
     * Get view
     *
     * @return integer
     */
    public function getView() {
        return $this -> view;
    }


    /**
     * Set rate
     *
     * @param \LCV\CoreBundle\Entity\Rating $rate
     * @return Article
     */
    public function setRate(\LCV\CoreBundle\Entity\Rating $rate = null)
    {
        $this->rate = $rate;

        return $this;
    }

    /**
     * Get rate
     *
     * @return \LCV\CoreBundle\Entity\Rating 
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * Set new
     *
     * @param \LCV\CoreBundle\Entity\News $new
     * @return Article
     */
    public function setNew(\LCV\CoreBundle\Entity\News $new = null)
    {
        $this->new = $new;

        return $this;
    }

    /**
     * Get new
     *
     * @return \LCV\CoreBundle\Entity\News 
     */
    public function getNew()
    {
        return $this->new;
    }
}
