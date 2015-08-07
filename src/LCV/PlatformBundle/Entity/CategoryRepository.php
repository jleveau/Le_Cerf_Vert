<?php

namespace LCV\PlatformBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * CategoryRepository
 *
 */
class CategoryRepository extends EntityRepository
{
    public function getArticles(){
        $query = $this->createQueryBuilder('c')
        ->leftJoin('c.articles','a')
        ->addSelect('a')
        ->orderBy('a.date','ASC')
        ->orderBy('c.name','ASC')
        ->getQuery();
        
        return $query->getResult();
    }
    
    
}
