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
    if ($entity instanceof Article) {
    	$em =$args->getEntityManager();
    	$article = $entity;
    	
		$news= new News();
		$content = new Content();
		$content->setDest($article->getId());
		$content->setType("article");
		$content->setDate($article->getDate());
		$content->setAuthor($article->getAuthor());
		$content->setTitle($article->getTitle());
		
		$article_content=$article->getContent();
		$abstract = substr($article_content,0,-255);
		if (!$abstract){
			$content->setAbstract($article->getContent());
		}
		else{
			$content->setAbstract($abstract . "...");
		}
		
		$news->setContent($content);
		
		$em -> persist($content);
        $em -> persist($news);
        $em -> flush();
 	  }
	
    return;
	}
	
	
}

