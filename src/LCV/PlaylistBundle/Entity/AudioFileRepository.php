<?php

namespace LCV\PlaylistBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * AudioFileRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AudioFileRepository extends EntityRepository
{

        public function isInPlaylist($id){
            $query = $this->createQueryBuilder('a')
                ->leftJoin('a.playlist_audios','pa')
                ->where('pa.audio = :id')
                ->setParameter('id',$id)
                
                ->orderBy('pa.sortOrder','ASC')
                ->getQuery();
                return !empty($query->getResult());
        }
    
}

