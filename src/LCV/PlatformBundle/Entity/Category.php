<?php
// src/LCV/PlatformBundle/Entity/Category.php

namespace LCV\PlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="LCV\PlatformBundle\Entity\CategoryRepository")
 * 
 */
class Category {
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;
    
        /**
     * @ORM\Column(name="ramovable", type="boolean")
     */
    private $removable;

    /**
     * @ORM\OneToMany(targetEntity="LCV\PlatformBundle\Entity\Article", mappedBy="category")
     */
    private $articles;

    public function getId() {
        return $this -> id;
    }

    public function setName($name) {
        $this -> name = $name;

        return $this;
    }

    public function getName() {
        return $this -> name;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->articles = new \Doctrine\Common\Collections\ArrayCollection();
        $this->removable=true;
    }

    /**
     * Add articles
     *
     * @param \LCV\PlatformBundle\Entity\Article $articles
     * @return Category
     */
    public function addArticle(\LCV\PlatformBundle\Entity\Article $article)
    {
        $this->articles[] = $article;
        $article->setCategory($this);

        return $this;
    }

    /**
     * Remove articles
     *
     * @param \LCV\PlatformBundle\Entity\Article $articles
     */
    public function removeArticle(\LCV\PlatformBundle\Entity\Article $articles)
    {
        $this->articles->removeElement($articles);
    }

    /**
     * Get articles
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getArticles()
    {
        return $this->articles;
    }
    
    public function __toString() {
    	return $this->name;
    }

    /**
     * Set removable
     *
     * @param boolean $removable
     * @return Category
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
}
