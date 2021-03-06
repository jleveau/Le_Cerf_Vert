<?php

namespace LCV\PlaylistBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * PlaylistCategoryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PlaylistCategoryRepository extends EntityRepository
{
    
        public function getPlaylists(){
        $query = $this->createQueryBuilder('c')
        ->leftJoin('c.playlists','p')
        ->addSelect('p')
        ->orderBy('p.date','ASC')
        ->orderBy('c.name','ASC')
        ->getQuery();
        
        return $query->getResult();
    }
    
}
