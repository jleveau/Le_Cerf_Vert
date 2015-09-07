<?php

/**
 * This file is part of the <name> project.
 *
 * (c) <yourname> <youremail>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\MediaBundle\Entity;

use Sonata\MediaBundle\Entity\BaseMedia as BaseMedia;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="sonata_media")
 */
class Media extends BaseMedia {
    /**
     * @var integer $id
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $galleryHasMedias;

    /**
     * Get id
     *
     * @return integer $id
     */
    public function getId() {
        return $this -> id;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this -> galleryHasMedias = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add galleryHasMedias
     *
     * @param \Application\Sonata\MediaBundle\Entity\GalleryHasMedia $galleryHasMedias
     * @return Media
     */
    public function addGalleryHasMedia(\Application\Sonata\MediaBundle\Entity\GalleryHasMedia $galleryHasMedias) {
        $this -> galleryHasMedias[] = $galleryHasMedias;

        return $this;
    }

    /**
     * Remove galleryHasMedias
     *
     * @param \Application\Sonata\MediaBundle\Entity\GalleryHasMedia $galleryHasMedias
     */
    public function removeGalleryHasMedia(\Application\Sonata\MediaBundle\Entity\GalleryHasMedia $galleryHasMedias) {
        $this -> galleryHasMedias -> removeElement($galleryHasMedias);
    }

    /**
     * Get galleryHasMedias
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGalleryHasMedias() {
        return $this -> galleryHasMedias;
    }
}
