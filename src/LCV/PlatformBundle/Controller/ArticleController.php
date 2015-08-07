<?php

namespace LCV\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use LCV\PlatformBundle\Entity\Article;
use LCV\PlatformBundle\Entity\Image;
use LCV\PlatformBundle\Entity\Comment;
use LCV\PlatformBundle\Entity\Skill;
use LCV\PlatformBundle\Entity\ArticleSkill;
use LCV\PlatformBundle\Form\ArticleType;
use LCV\PlatformBundle\Form\ArticleEditType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


class ArticleController extends Controller {

    public function indexAction() {

        
        $listCategories = $this -> getDoctrine() -> getManager() -> getRepository('LCVPlatformBundle:Category') -> getArticles();
        //$listArticles = $this -> getDoctrine() -> getManager() -> getRepository('LCVPlatformBundle:Article') -> getArticles();
        $listArticles = $this -> getDoctrine() -> getManager() -> getRepository('LCVPlatformBundle:Article') -> getArticlesWithCategory();
        // On donne toutes les informations nécessaires à la vue
        return $this -> render('LCVPlatformBundle:Article:index.html.twig', array('listCategories' => $listCategories, 'listArticles' =>  $listArticles));
    }
    
    public function listAction() {
    	$listArticles = $this -> getDoctrine() -> getManager() -> getRepository('LCVPlatformBundle:Article') -> getArticlesWithCategory();
    	return $this -> render('LCVPlatformBundle:Article:list_articles.html.twig', array('listArticles' => $listArticles));
    }

    public function menuAction($limit = 5) {
        $listArticles = $this -> getDoctrine() -> getManager() -> getRepository('LCVPlatformBundle:Article') -> getLastArticles(5);

        return $this -> render('LCVPlatformBundle:Article:menu.html.twig', array('listArticles' => $listArticles));
    }

    public function viewAction($id) {
        $em = $this -> getDoctrine() -> getManager();

        $repository = $em -> getRepository('LCVPlatformBundle:Article');
        $article = $repository -> find($id);
        $article -> updateDate();

        $em -> flush();

        if (null === $article) {
            throw new NotFoundHttpException("L'annonce d'id " . $id . "n'existe pas");
        }
        
        return $this -> render('LCVPlatformBundle:Article:view.html.twig', array('article' => $article));
    }

    public function addAction($category_id,Request $request) {

        $article = new Article();
        $em = $this -> getDoctrine() -> getManager();
 		if ($category_id == null)
 			$category=$em->getRepository('LCVPlatformBundle:Category') -> findOneByName('Default');
 		else
        	$category=$em->getRepository('LCVPlatformBundle:Category') -> findOneById($category_id);
        
        $article->setCategory($category);
    
        $form = $this -> createForm(new ArticleType(), $article);
        if ($form -> handleRequest($request) -> isValid()) {
            
            //On relie l'article et l'utilisateur connecté
            $user = $this->getUser();
            $article->setAuthor($user);
            
            // On l'enregistre notre objet $article dans la base de données, par exemple

            $em -> persist($article);
            $em -> flush();

            $request -> getSession() -> getFlashBag() -> add('notice', 'Annonce bien enregistrée.');
            return $this -> redirect($this -> generateUrl('lcv_platform_view', array('id' => $article -> getId())));
        }
        return $this -> render('LCVPlatformBundle:Article:add.html.twig', array('article' => $article,'form' => $form -> createView()));
    }

    public function editAction($id, Request $request) {
        $em = $this -> getDoctrine() -> getManager();

        // On récupère l'annonce $id
        $article = $em -> getRepository('LCVPlatformBundle:Article') -> find($id);

        if (null === $article) {
            throw new NotFoundHttpException("L'annonce d'id " . $id . " n'existe pas.");
        }
        $form = $this -> createForm(new ArticleEditType(), $article);

        if ($form -> handleRequest($request) -> isValid()) {
            // Inutile de persister ici, Doctrine connait déjà notre annonce
            $em -> flush();

            $request -> getSession() -> getFlashBag() -> add('notice', 'Annonce bien modifiée.');

            return $this -> redirect($this -> generateUrl('lcv_platform_view', array('id' => $article -> getId())));
        }

        return $this -> render('LCVPlatformBundle:Article:edit.html.twig', array('form' => $form -> createView(), 'article' => $article // Je passe également l'annonce à la vue si jamais elle veut l'afficher
        ));
        return $this -> render('LCVPlatformBundle:Article:edit.html.twig', array('article' => $article));
    }

    public function deleteAction($id, Request $request) {
        $em = $this -> getDoctrine() -> getManager();

        // On récupère l'annonce $id
        $article = $em -> getRepository('LCVPlatformBundle:Article') -> find($id);

        if (null === $article) {
            throw new NotFoundHttpException("L'annonce d'id " . $id . " n'existe pas.");
        }

        // On crée un formulaire vide, qui ne contiendra que le champ CSRF
        // Cela permet de protéger la suppression d'annonce contre cette faille
        $form = $this -> createFormBuilder() -> getForm();

        if ($form -> handleRequest($request) -> isValid()) {
            $em -> remove($article);
            $em -> flush();

            $request -> getSession() -> getFlashBag() -> add('info', "L'annonce a bien été supprimée.");

            return $this -> redirect($this -> generateUrl('lcv_platform_index'));
        }

        // Si la requête est en GET, on affiche une page de confirmation avant de supprimer
        return $this -> render('LCVPlatformBundle:Article:delete.html.twig', array('article' => $article, 'form' => $form -> createView()));
    }



    public function testAction() {
        $article = new Article();
        $article -> setTitle("Recherche développeur !");

        $em = $this -> getDoctrine() -> getManager();
        $em -> persist($article);
        $em -> flush();
        // C'est à ce moment qu'est généré le slug

        return new Response('Slug généré : ' . $article -> getSlug());
        // Affiche « Slug généré : recherche-developpeur »
    }

}
