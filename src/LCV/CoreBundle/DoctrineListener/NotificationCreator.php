<?php

namespace LCV\CoreBundle\DoctrineListener;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManager;

use Doctrine\ORM\Event\LifecycleEventArgs;
use LCV\PlatformBundle\Entity\Article as Article;
use LCV\CommentBundle\Entity\Comment;
use LCV\CoreBundle\Entity\Notification;
use Application\Sonata\UserBundle\Entity\User;

class NotificationCreator{
   protected $container;
   
   public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    
  public function postPersist(LifecycleEventArgs $args)
  {
    $entity = $args->getEntity();
    
    // On veut envoyer un email que pour les entités Application
    if ($entity instanceof Comment) {
        $em =$args->getEntityManager();
        $comment = $entity;
       ;
        if ( $article=$comment->getArticle()){
            if ($article->getAuthor()==$this->container->get('security.context')->getToken()->getUser())
                return;
            
            $notification= new Notification();
            $notification->setUser($article->getAuthor());
            $notification->setType('article_comment');
            $notification->setArticle($article);
            $notification->setMessage("<small> Le ".date("m/d/Y \à H:i:s",$comment->getDate()->getTimestamp())."</small> - <strong>".$comment->getAuthor()."</strong> a commenté votre article ");
        }
        else if ($playlist=$comment->getPlaylist()){
            if ($playlist->getAuthor()==$this->container->get('security.context')->getToken()->getUser())
                return;
            
            $notification= new Notification();
            $notification->setUser($playlist->getAuthor());
            $notification->setType('playlist_comment');
            $notification->setPlaylist($playlist);
            $notification->setMessage("<small> Le ".date("m/d/Y \à H:i:s",$comment->getDate()->getTimestamp())."</small> - <strong>".$comment->getAuthor()."</strong> a commenté votre playlist ");
        }
        $em -> persist($notification);
        $em -> flush();
      }
    
    return;
    }
}

