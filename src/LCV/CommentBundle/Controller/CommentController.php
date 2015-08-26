<?php

namespace LCV\CommentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use LCV\CommentBundle\Entity\Comment;
use LCV\CommentBundle\Form\CommentType;
use Symfony\Component\HttpFoundation\Request;

class CommentController extends Controller {
    
    public function viewAction($article_id) {
        $em = $this -> getDoctrine() -> getManager();
        $list_comment = $em -> getRepository('LCVCommentBundle:Comment') -> getArticleComments($article_id);

        return $this -> render('LCVCommentBundle:Comment:show.html.twig', array('list_comment' => $list_comment));
    }

    public function writeAction($article_id, Request $request) {

    }

    public function deleteAction() {
        return $this -> render('LCVCommentBundle:Comment:delete.html.twig', array(
        // ...
        ));
    }

    public function editAction() {
        return $this -> render('LCVCommentBundle:Comment:edit.html.twig', array(
        // ...
        ));
    }

}
