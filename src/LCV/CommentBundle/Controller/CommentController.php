<?php

namespace LCV\CommentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use LCV\CommentBundle\Entity\Comment;
use LCV\CommentBundle\Form\CommentType;
use LCV\CommentBundle\Form\EditCommentType;
use Symfony\Component\HttpFoundation\Request;

class CommentController extends Controller {
    
    public function viewAction($article_id, Request $request) {
        $em = $this -> getDoctrine() -> getManager();
        $list_comment = $em -> getRepository('LCVCommentBundle:Comment') -> getArticleComments($article_id);
        $forms=array();
        $form_view=array();
        $user_id=$this->getUser()->getId();
        
        foreach ($list_comment as $comment){
           $comment_id=$comment->getId();
           $forms[$comment_id] = $this->get('form.factory')->create(new EditCommentType($comment), $comment);
    //       $this -> createForm(new EditCommentType(), $comment,array('id'=>$comment_id));
           
            if ($forms[$comment_id] -> handleRequest($request) -> isValid()) {
                $comment->updateDate();
                $em -> flush(); //On flush pour créer l'id du content
    
                $request -> getSession() -> getFlashBag() -> add('success', 'Commentaire édité');
                return $this -> redirect($this -> generateUrl('lcv_platform_view',array('id' => $article_id)));
            }
            $form_view[$comment_id]=$forms[$comment_id]->createView();
        }
        return $this -> render('LCVCommentBundle:Comment:show.html.twig', array('list_comment' => $list_comment,'forms'=>$form_view,'article_id'=>$article_id,'user_id'=>$user_id));
    }

    public function writeAction($article_id, Request $request) {

    }

    public function deleteAction($comment_id,Request $request) {
        $em = $this -> getDoctrine() -> getManager();

        // On récupère l'article $id
        $comment = $em -> getRepository('LCVCommentBundle:Comment') -> find($comment_id);

        if (null === $comment) {
            throw new NotFoundHttpException("Le commentaire d'id " . $id . " n'existe pas.");
        }

        // On crée un formulaire vide, qui ne contiendra que le champ CSRF
        // Cela permet de protéger la suppression d'article contre cette faille

        $em -> remove($comment);
        $em -> flush();

        $request -> getSession() -> getFlashBag() -> add('success', "Le commentaire a bien été supprimé.");
        return $this -> redirect($this -> generateUrl('lcv_platform_view',array('id' => $comment->getArticle()->getId())));
        
        var_dump("inherher");

        

    }

    public function editAction() {
        return $this -> render('LCVCommentBundle:Comment:edit.html.twig', array(
        // ...
        ));
    }

}
