<?php

namespace LCV\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AccueilController extends Controller
{
    public function accueilAction()
    {
    	
    	    	
        return $this->render('LCVCoreBundle:Accueil:accueil.html.twig');   
    }
    
    public function newsAction($limit=10){
    	$listNews = $this -> getDoctrine() -> getManager() -> getRepository('LCVCoreBundle:News') -> getNews();
    	
    	return $this -> render('LCVCoreBundle:Accueil:news.html.twig', array('list_news' => $listNews));
    }

}
