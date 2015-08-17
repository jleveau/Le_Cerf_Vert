<?php

namespace LCV\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class AccueilController extends Controller {
    public function accueilAction() {

        return $this -> render('LCVCoreBundle:Accueil:accueil.html.twig');
    }

    public function newsAction($limit = 10) {
        $listNews = $this -> getDoctrine() -> getManager() -> getRepository('LCVCoreBundle:News') -> getNews();

        return $this -> render('LCVCoreBundle:Accueil:news.html.twig', array('list_news' => $listNews));
    }

    public function news_headerAction($content_id, $type) {
        $em = $this -> getDoctrine();
        $value;
        switch ($type) {
            case 'article' :
                $value = $em -> getRepository('LCVPlatformBundle:Article') -> findOneById($content_id);
                return $this -> render('LCVCoreBundle:Accueil:news_article_header.html.twig', array('value' => $value));

            default :
                $value = null;
                break;
        }
    }

}
