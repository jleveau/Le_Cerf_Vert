<?php

namespace LCV\PlatformBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use LCV\PlatformBundle\Validator\Antiflood;

/**
 * Article
 *
 * @ORM\Table(name="lcv_article")
 * @ORM\Entity(repositoryClass="LCV\PlatformBundle\Entity\ArticleRepository")
 * @ORM\HasLifeCycleCallbacks()
 * @UniqueEntity("title")
 */
class Article {
    /**
     * @ORM\ManyToOne(targetEntity="LCV\PlatformBundle\Entity\Category", inversedBy="articles")
     */
    private $category;

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
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User", inversedBy="articles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     * @Assert\DateTime()
     */
    private $date;

    /**
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, unique=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     * @Assert\NotBlank()
     * @Antiflood()
     */
    private $content;


    /**
     * @ORM\Column(name="nb_comments", type="integer")
     */
    private $nbComments = 0;

    /**
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(length=128, unique=true)
     */
    private $slug;

    /////////////////////
    /**
     * @ORM\PreUpdate
     */
    public function updateDate() {
        $this -> setUpdatedAt(new \DateTime());
    }

    public function increaseComment() {
        $this -> nbComments++;
    }

    public function decreaseComment() {
        $this -> nbComments--;
    }
    

    /////////////////////

    public function __construct() {
        $this -> date = new \DateTime();
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
     * Set nbComments
     *
     * @param integer $nbComments
     * @return Article
     */
    public function setNbComments($nbComments) {
        $this -> nbComments = $nbComments;

        return $this;
    }

    /**
     * Get nbComments
     *
     * @return integer
     */
    public function getNbComments() {
        return $this -> nbComments;
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
    public function setCategory(\LCV\PlatformBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \LCV\PlatformBundle\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set author
     *
     * @param \Application\Sonata\UserBundle\Entity\User $author
     * @return Article
     */
    public function setAuthor(\Application\Sonata\UserBundle\Entity\User $author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return \Application\Sonata\UserBundle\Entity\User 
     */
    public function getAuthor()
    {
        return $this->author;
    }
    
    public function __toString() {
    	return $this->title;
    }
}
