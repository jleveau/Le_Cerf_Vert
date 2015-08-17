<?php
// src/LCV/CoreBundle/DoctrineListener/NewsCreator.php

namespace LCV\CoreBundle\DoctrineListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use LCV\PlatformBundle\Entity\Article as Article;
use LCV\CoreBundle\Entity\News;
use LCV\CoreBundle\Entity\Content;

class NewsCreator
{
  public function postPersist(LifecycleEventArgs $args)
  {
  	
    $entity = $args->getEntity();
    
    // On veut envoyer un email que pour les entités Application
    if ($entity instanceof Content) {
    	$em =$args->getEntityManager();
    	$content = $entity;
    	
		$news= new News();
		$news->setContent($content);
		
        $em -> persist($news);
        $em -> flush();
 	  }
	
    return;
	}
	
	
}

