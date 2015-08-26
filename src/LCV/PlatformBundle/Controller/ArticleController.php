<?php

namespace LCV\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use LCV\PlatformBundle\Entity\Article;
use LCV\PlatformBundle\Entity\Image;
use LCV\PlatformBundle\Form\ArticleType;
use LCV\PlatformBundle\Form\ArticleEditType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use LCV\CommentBundle\Entity\Comment;
use LCV\CommentBundle\Form\CommentType;
use LCV\PlatformBundle\Form\CategoryType;
use LCV\PlatformBundle\Entity\Category;
use LCV\CoreBundle\Entity\Rating;
use LCV\CoreBundle\Entity\Vote;
use LCV\CoreBundle\Form\VoteType;

use LCV\PlatformBundle\Entity\ArticleRepository;
use Doctrine\ORM\Id\AssignedGenerator;


class ArticleController extends Controller {

    public function indexAction(Request $request) {
        
        $listCategories = $this -> getDoctrine() -> getManager() -> getRepository('LCVPlatformBundle:Category') -> getArticles();
        //$listArticles = $this -> getDoctrine() -> getManager() -> getRepository('LCVPlatformBundle:Article') -> getArticles();
        $listArticles = $this -> getDoctrine() -> getManager() -> getRepository('LCVPlatformBundle:Article') -> getArticlesWithCategory();
        // On donne toutes les informations nécessaires à la vue
        
        $category = new Category();
        $em = $this -> getDoctrine() -> getManager();
         
        $form = $this -> createForm(new CategoryType(), $category);
        if ($form -> handleRequest($request) -> isValid()) {
            
           
            $em -> persist($category);
            $em -> flush(); //On flush pour créer l'id du content

            $request -> getSession() -> getFlashBag() -> add('success', 'Catégorie bien enregistrée.');
            return $this -> redirect($this -> generateUrl('lcv_platform_index'));
        }
        return $this -> render('LCVPlatformBundle:Article:index.html.twig', array('listCategories' => $listCategories, 'listArticles' =>  $listArticles,'form' => $form -> createView()));
    }
    
    public function listAction() {
    	$listArticles = $this -> getDoctrine() -> getManager() -> getRepository('LCVPlatformBundle:Article') -> getArticlesWithCategory();
    	return $this -> render('LCVPlatformBundle:Article:list_articles.html.twig', array('listArticles' => $listArticles));
    }

    public function menuAction($limit = 5) {
        $listArticles = $this -> getDoctrine() -> getManager() -> getRepository('LCVPlatformBundle:Article') -> getLastArticles(5);

        return $this -> render('LCVPlatformBundle:Article:menu.html.twig', array('listArticles' => $listArticles));
    }

    public function viewAction($id,Request $request) {
        $em = $this -> getDoctrine() -> getManager();

        $repository = $em -> getRepository('LCVPlatformBundle:Article');
        $article = $repository -> find($id);

        if (null === $article) {
            throw new NotFoundHttpException("L'article d'id " . $id . "n'existe pas");
        }
        
        //Vues
        $article -> incViews();
        $em -> flush();
        
        //Votes
        $vote=$repository->getArticleVoteUser($id,$this->getUser());
        if (count($vote)==0){
            $vote = new Vote();
            $em -> persist($vote);
            $vote->setVoter($this->getUser());
            $vote->setRating($article->getRate());
            
        }
        else{
            $vote=$vote[0]->getRate()->getVotes();
            $vote=$vote[0];
        }
        $rate=$article->getRate();
        $vote->setValue($rate->getRate());
        
        $form_vote = $this -> createForm(new VoteType(), $vote);
        
        if ($form_vote -> handleRequest($request) -> isValid()) {
            
            $this->get("session")->getFlashBag()->add("success", "Votre vote à été pris en compte !");
            
            //Definition de l'auteur et de l'article

            $em -> flush();

            return $this->redirect($this -> generateUrl('lcv_platform_view', array('id' => $article->getId())));
        }
        
        ////Ecriture de Commentaires
        $comment = new Comment();
            
        $form_comment = $this -> createForm(new CommentType(), $comment);
        if ($form_comment -> handleRequest($request) -> isValid()) {
            
           $this->get("session")->getFlashBag()->add("success", "Commentaire ajouté !");
            
            //Definition de l'auteur et de l'article
            $this->getUser()->addComment($comment);
            $article->addComment($comment);
            
            $em -> persist($comment);
            $em -> flush();

            return $this->redirect($this -> generateUrl('lcv_platform_view', array('id' => $article->getId())));
        }
        return $this -> render('LCVPlatformBundle:Article:view.html.twig', array('article_vote'=>$vote,
                        'article' => $article,
                        'form' => $form_comment -> createView(),
                        'form_vote'=> $form_vote -> createView(),
                        'rate' => $rate));
    }

    public function addAction($categoryId,Request $request) {

        $article = new Article();
        
        $em = $this -> getDoctrine() -> getManager();
 		if ($categoryId == null){
 			$category=$em->getRepository('LCVPlatformBundle:Category') -> findOneByName('Default'); 
        }
 		else{
        	$category=$em->getRepository('LCVPlatformBundle:Category') -> findOneById($categoryId);
        }
        $article->setCategory($category);
    
        $form = $this -> createForm(new ArticleType(), $article);
        if ($form -> handleRequest($request) -> isValid()) {
            $article->setAuthor($this->getUser());
            $article->setRate(new Rating());

            $em -> persist($article);
            $em -> flush();

            $request -> getSession() -> getFlashBag() -> add('success', 'Article bien enregistré.');
            return $this -> redirect($this -> generateUrl('lcv_platform_view', array('id' => $article -> getId())));
        }
        return $this -> render('LCVPlatformBundle:Article:add.html.twig', array('article' => $article,'form' => $form -> createView()));
    }

    public function editAction($id, Request $request) {
        $em = $this -> getDoctrine() -> getManager();

        // On récupère l'article $id
        $article = $em -> getRepository('LCVPlatformBundle:Article') -> find($id);

        if (null === $article) {
            throw new NotFoundHttpException("L'article d'id " . $id . " n'existe pas.");
        }
        $form = $this -> createForm(new ArticleEditType(), $article);

        if ($form -> handleRequest($request) -> isValid()) {
            // Inutile de persister ici, Doctrine connait déjà notre article
            $article -> updateDate();
            $em -> flush();

            $request -> getSession() -> getFlashBag() -> add('notice', 'Article bien modifiée.');

            return $this -> redirect($this -> generateUrl('lcv_platform_view', array('id' => $article -> getId())));
        }

        return $this -> render('LCVPlatformBundle:Article:edit.html.twig', array('form' => $form -> createView(), 'article' => $article // Je passe également l'article à la vue si jamais elle veut l'afficher
        ));
        return $this -> render('LCVPlatformBundle:Article:edit.html.twig', array('article' => $article));
    }

    public function deleteAction($id, Request $request) {
        $em = $this -> getDoctrine() -> getManager();

        // On récupère l'article $id
        $article = $em -> getRepository('LCVPlatformBundle:Article') -> find($id);

        if (null === $article) {
            throw new NotFoundHttpException("L'article d'id " . $id . " n'existe pas.");
        }

        // On crée un formulaire vide, qui ne contiendra que le champ CSRF
        // Cela permet de protéger la suppression d'article contre cette faille
        $form = $this -> createFormBuilder() -> getForm();

        if ($form -> handleRequest($request) -> isValid()) {
            $em -> remove($article);
            $em -> flush();

            $request -> getSession() -> getFlashBag() -> add('success', "L'article a bien été supprimée.");

            return $this -> redirect($this -> generateUrl('lcv_platform_index'));
        }

        // Si la requête est en GET, on affiche une page de confirmation avant de supprimer
        return $this -> render('LCVPlatformBundle:Article:delete.html.twig', array('article' => $article, 'form' => $form -> createView()));
    }

    public function deleteCategoryAction($id, Request $request) {
        $em = $this -> getDoctrine() -> getManager();        
        $category = $em -> getRepository('LCVPlatformBundle:Category') -> findOneById($id);
        if (null === $category) {
            throw new NotFoundHttpException("La Categorie d'id " . $id . " n'existe pas.");
        }
        $em -> remove($category);
            $em -> flush();
            
       $listCategories = $this -> getDoctrine() -> getManager() -> getRepository('LCVPlatformBundle:Category') -> getArticles();
        //$listArticles = $this -> getDoctrine() -> getManager() -> getRepository('LCVPlatformBundle:Article') -> getArticles();
        $listArticles = $this -> getDoctrine() -> getManager() -> getRepository('LCVPlatformBundle:Article') -> getArticlesWithCategory();
        // On donne toutes les informations nécessaires à la vue
        $request -> getSession() -> getFlashBag() -> add('success', 'Catégorie bien supprimée.');
        return $this -> redirect($this -> generateUrl('lcv_platform_index'));    
    }
}
