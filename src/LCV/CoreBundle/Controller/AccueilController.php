<?php

namespace LCV\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class AccueilController extends Controller {
    public function accueilAction() {

        return $this -> render('LCVCoreBundle:Accueil:accueil.html.twig');
    }

    public function newsAction($limit) {
        $listNews = $this -> getDoctrine() -> getManager() -> getRepository('LCVCoreBundle:News') -> getNews();
        $listNews = array_slice($listNews, 0, $limit);
        return $this -> render('LCVCoreBundle:Accueil:news.html.twig', array('list_news' => $listNews));
    }

    public function newsHeaderAction($news_id) {        
        $em = $this -> getDoctrine();
        $news= $em -> getRepository('LCVCoreBundle:News') -> find($news_id);
        switch ($news->getType()) {
            case 'article' :
                     $article = $em -> getRepository('LCVPlatformBundle:Article') -> find($news->getArticle());
                return $this -> render('LCVCoreBundle:Accueil:news_article_header.html.twig', array('article' => $article));

            default :
                $value = null;
                break;
        }
    }
}
