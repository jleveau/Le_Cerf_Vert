<?php

namespace Application\Sonata\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Application\Sonata\UserBundle\Entity\Invitation;
use Application\Sonata\UserBundle\Form\Type\AskInvitationFormType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class UserController extends Controller
{
    public function profilePlaylistsAction(){
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $list_playlists=$user->getPlaylists();
        return $this->render('ApplicationSonataUserBundle:Profile:profilePlaylists.html.twig', array(
            'list_playlists' => $list_playlists
            ));    
    }
    
    public function profileArticlesAction()
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $list_articles=$user->getArticles();
        return $this->render('ApplicationSonataUserBundle:Profile:profileArticles.html.twig', array(
            'list_articles' => $list_articles
            ));    
    }
    
    public function profileDeletePlaylistAction($id, Request $request) {
        $em = $this -> getDoctrine() -> getManager();

        // On récupère l'annonce $id 
        $playlist = $em -> getRepository('LCVPlaylistBundle:Playlist') -> find($id);

        if (null === $playlist) {
            throw new NotFoundHttpException("La playlist d'id " . $id . " n'existe pas.");
        }

        $em -> remove($playlist);
        $em -> flush();

        $request -> getSession() -> getFlashBag() -> add('info', "La playlist a bien été supprimée.");
        
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $list_playlists=$user->getPlaylists();
        return $this->render('ApplicationSonataUserBundle:Profile:profilePlaylists.html.twig', array(
            'list_playlists' => $list_playlists
            ));    
    }
    
    public function profileDeleteArticleAction($id, Request $request) {
        $em = $this -> getDoctrine() -> getManager();

        // On récupère l'annonce $id 
        $article = $em -> getRepository('LCVPlatformBundle:Article') -> find($id);

        if (null === $article) {
            throw new NotFoundHttpException("L'annonce d'id " . $id . " n'existe pas.");
        }

        $em -> remove($article);
        $em -> flush();

        $request -> getSession() -> getFlashBag() -> add('info', "L'article a bien été supprimée.");
        
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $list_articles=$user->getArticles();
        return $this->render('ApplicationSonataUserBundle:Profile:profileArticles.html.twig', array(
            'list_articles' => $list_articles
            ));    
    }

    public function whoIsOnlineAction()
    {
        $users = $this->getDoctrine()->getManager()->getRepository('ApplicationSonataUserBundle:User')->getActive();
        
        return $this->render('ApplicationSonataUserBundle::whoIsOnline.html.twig', array(
            'users' => $users
            ));    
    }
    
    public function askInvitationAction(Request $request){
         $em = $this -> getDoctrine() -> getManager();
        // On récupère l'article $id
        $invitation = new Invitation();

        $form = $this -> createForm(new AskInvitationFormType(), $invitation);

        if ($form -> handleRequest($request) -> isValid()) {
            $message = \Swift_Message::newInstance()
                ->setSubject('Le-Cerf-Vert - Demande Invitation')
                ->setFrom(array('Le-Cerf-Vert@mail.com' => 'Le-Cerf-Vert-Website'))
                ->setTo(array('oursgroumy@gmail.com' => 'Admin'))
                ->setBody("Demande d'inscription de la part de ".$invitation->getEmail()."\n"."Code de validation : ".$invitation->getCode());
            ;
            $this->container->get('mailer')->send($message);
            $mailer=$this->get('mailer');
            $spool = $mailer->getTransport()->getSpool();
            $transport = $this->get('swiftmailer.transport.real');
            
            $spool->flushQueue($transport);
            
            $invitation->send();
            $em -> persist($invitation);
            $em -> flush();
            
            $request -> getSession() -> getFlashBag() -> add('success', 'Demande prise en compte');
            
            return $this -> redirect($this -> generateUrl('_accueil'));
        }
        return $this -> render('ApplicationSonataUserBundle:Invitation:ask_invitation.html.twig', array('form' => $form -> createView()));
    }
}
