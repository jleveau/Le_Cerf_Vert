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
  	
    $article = $args->getEntity();
    
    // On veut envoyer un email que pour les entités Application
    if ($article instanceof Article) {
    	$em =$args->getEntityManager();
        
        
    	
		$news= new News();

        $news->setType("article");
        $news->setArticle($article);
        $news->createAbstract($article->getContent());
        $em -> persist($news);
        $em -> flush(); //On flush pour créer l'id du content
 	  }
    return;
	}
	
	
}

