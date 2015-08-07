<?php

namespace LCV\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * News
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="LCV\CoreBundle\Entity\NewsRepository")
 */
class News
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
     * @var LCV\CoreBundle\Entity\Content
     *
     * @ORM\OneToOne(targetEntity="LCV\CoreBundle\Entity\Content")
     */
    private $content;


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
     * Set content
     *
     * @param LCV\CoreBundle\Entity\Content
     * @return News
     */
    public function setContent(Content $content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return LCV\CoreBundle\Entity\Content
     */
    public function getContent()
    {
        return $this->content;
    }
}
