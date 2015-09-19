<?php
// src/LCV/CoreBundle/DoctrineListener/NewsCreator.php

namespace LCV\CoreBundle\DoctrineListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use LCV\PlatformBundle\Entity\Article as Article;
use LCV\PlaylistBundle\Entity\Playlist as Playlist;
use LCV\CoreBundle\Entity\News;
use LCV\CoreBundle\Entity\Content;

class NewsCreator
{
  public function postPersist(LifecycleEventArgs $args)
  {
  	
    $obj = $args->getEntity();
    
    // On veut envoyer un email que pour les entités Application
    if ($obj instanceof Article) {
        $article=$obj;
    	$em =$args->getEntityManager();
        
        
    	
		$news= new News();

        $news->setType("article");
        $news->setArticle($article);
        $news->createAbstract($article->getContent());
        $em -> persist($news);
        $em -> flush(); //On flush pour créer l'id du content
 	  }
	
	if ($obj instanceof Playlist) {
	    $playlist=$obj;
        $em =$args->getEntityManager();

        $news= new News();

        $news->setType("playlist");
        $news->setPlaylist($playlist);
        $news->createAbstract("");
        $em -> persist($news);
        $em -> flush(); //On flush pour créer l'id du content
      }
    return;
    }
	
}

