<?php

namespace LCV\PlaylistBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use LCV\PlaylistBundle\Entity\Playlist;
use LCV\PlaylistBundle\Entity\PlaylistCategory;
use LCV\PlaylistBundle\Form\PlaylistType;
use LCV\PlaylistBundle\Form\EditPlaylistType;
use LCV\CommentBundle\Entity\Comment;
use LCV\CommentBundle\Form\CommentType;
use LCV\PlaylistBundle\Form\PlaylistCategoryType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use LCV\CoreBundle\Entity\Vote;
use LCV\CoreBundle\Entity\Rating;
use LCV\CoreBundle\Form\VoteType;

class PlaylistController extends Controller {

    public function viewAction($playlist_id, Request $request) {
        $em = $this -> getDoctrine() -> getManager();
        $playlist = $em -> getRepository('LCVPlaylistBundle:Playlist') -> findOneById($playlist_id);
        if ($playlist === null) {
            throw new NotFoundHttpException("La Playlist n'existe pas");
        }
        //Vues
        $playlist -> incViews();
        $em -> flush();

        //Votes
         $repository = $em -> getRepository('LCVPlaylistBundle:Playlist');
         $vote=$repository->getPlaylistVoteUser($playlist_id,$this->getUser());
         if (count($vote)==0){
             $vote = new Vote();
             $em -> persist($vote);
             $vote->setVoter($this->getUser());
             $vote->setRating($playlist->getRate());
         }
         else{
             $vote=$vote[0]->getRate()->getVotes();
             $vote=$vote[0];
         }
         
             $rate=$playlist->getRate();
             $vote->setValue($rate->getRate());
    
             $form_vote = $this -> createForm(new VoteType(), $vote);

         if ($form_vote -> handleRequest($request) -> isValid()) {

             $this->get("session")->getFlashBag()->add("success", "Votre vote à été pris en compte !");
    
    
             $em -> flush();
    
             return $this->redirect($this -> generateUrl('lcv_playlist_view', array('playlist_id' => $playlist->getId())));
         }

        ////Ecriture de Commentaires
        $comment = new Comment();

        $form_comment = $this -> createForm(new CommentType(), $comment);
        if ($form_comment -> handleRequest($request) -> isValid()) {

            $this -> get("session") -> getFlashBag() -> add("success", "Commentaire ajouté !");
            //Definition de l'auteur et de l'article
            $this -> getUser() -> addComment($comment);
            $playlist -> addComment($comment);

            $em -> persist($comment);
            $em -> flush();

            return $this -> redirect($this -> generateUrl('lcv_playlist_view', array('playlist_id' => $playlist -> getId())));
        }
        return $this -> render('LCVPlaylistBundle:Playlist:view.html.twig', array(
                                 'article_vote'=>$vote,
                                'playlist' => $playlist, 
                                'form_vote'=> $form_vote -> createView(),
                                'form' => $form_comment -> createView(),
                                'rate' => $rate));
    }

    public function MenuAction($limit = 5) {
        $listPlaylist = $this -> getDoctrine() -> getManager() -> getRepository('LCVPlaylistBundle:Playlist') -> getLastPlaylists(5);

        return $this -> render('LCVPlaylistBundle:Playlist:menu.html.twig', array('listPlaylist' => $listPlaylist));
    }

    public function addAction($categoryId, Request $request) {
        $playlist = new Playlist();

        $em = $this -> getDoctrine() -> getManager();
        if ($categoryId == null) {
            $category = $em -> getRepository('LCVPlaylistBundle:PlaylistCategory') -> findOneByName('Default');
        } else {
            $category = $em -> getRepository('LCVPlaylistBundle:PlaylistCategory') -> findOneById($categoryId);
        }
        $playlist -> setCategory($category);

        $form = $this -> createForm(new PlaylistType(), $playlist);
        if ($form -> handleRequest($request) -> isValid()) {
            $playlist -> setAuthor($this -> getUser());
            $playlist->setRate(new Rating());

            $em -> persist($playlist);
            $em -> flush();

            $request -> getSession() -> getFlashBag() -> add('success', 'Playlist bien enregistrée !');
            return $this -> redirect($this -> generateUrl('lcv_playlist_edit', array('playlist_id' => $playlist -> getId())));
        }
        return $this -> render('LCVPlaylistBundle:Playlist:add.html.twig', array('playlist' => $playlist, 'form' => $form -> createView()));
    }

    public function deleteAction($playlist_id, Request $request) {
        $em = $this -> getDoctrine() -> getManager();

        // On récupère l'article $id
        $playlist = $em -> getRepository('LCVPlaylistBundle:Playlist') -> find($playlist_id);

        if (null === $playlist) {
            throw new NotFoundHttpException("La playlist d'id " . $playlist_id . " n'existe pas.");
        }

        // On crée un formulaire vide, qui ne contiendra que le champ CSRF
        // Cela permet de protéger la suppression d'article contre cette faille
        $form = $this -> createFormBuilder() -> getForm();

        if ($form -> handleRequest($request) -> isValid()) {
            $em -> remove($playlist);
            $em -> flush();

            $request -> getSession() -> getFlashBag() -> add('success', "La playlist a bien été supprimée.");

            return $this -> redirect($this -> generateUrl('lcv_playlist_index'));
        }

        // Si la requête est en GET, on affiche une page de confirmation avant de supprimer
        return $this -> render('LCVPlaylistBundle:Playlist:delete.html.twig', array('playlist' => $playlist, 'form' => $form -> createView()));
    }

    public function editAction($playlist_id, Request $request) {
        $em = $this -> getDoctrine() -> getManager();
        $playlist = $em -> getRepository('LCVPlaylistBundle:Playlist') -> getOneByIdInOrder($playlist_id);

        $form = $this -> createForm(new EditPlaylistType(), $playlist);
        foreach ($playlist->getPlaylistAudios() as $playlistaudio) {
            $playlistaudio -> getAudio() -> getFile();
        }
        if ($form -> handleRequest($request) -> isValid()) {

            $playlist -> updateDate();
            $em -> flush();

            $request -> getSession() -> getFlashBag() -> add('success', 'Playlist mise à jour !');
            return $this -> redirect($this -> generateUrl('lcv_playlist_edit', array('playlist_id' => $playlist -> getId())));
        }
        return $this -> render('LCVPlaylistBundle:Playlist:edit.html.twig', array('form' => $form -> createView(), 'playlist' => $playlist));
    }

    public function indexAction(Request $request) {
          
        $listCategories = $this -> getDoctrine() -> getManager() -> getRepository('LCVPlaylistBundle:PlaylistCategory') -> getPlaylists();
        //$listArticles = $this -> getDoctrine() -> getManager() -> getRepository('LCVPlatformBundle:Article') -> getArticles();
        $list_playlists = $this -> getDoctrine() -> getManager() -> getRepository('LCVPlaylistBundle:Playlist') -> getPlaylistsWithCategory();
        // On donne toutes les informations nécessaires à la vue
        
        $category = new PlaylistCategory();
        $em = $this -> getDoctrine() -> getManager();
         
        $form = $this -> createForm(new PlaylistCategoryType(), $category);
        if ($form -> handleRequest($request) -> isValid()) {
            
            $em -> persist($category);
            $em -> flush(); //On flush pour créer l'id du content

            $request -> getSession() -> getFlashBag() -> add('success', 'Catégorie bien enregistrée.');
            return $this -> redirect($this -> generateUrl('lcv_playlist_index'));
        }
        return $this -> render('LCVPlaylistBundle:Playlist:index.html.twig', array('listCategories' => $listCategories, 'list_playlists' =>  $list_playlists,'form' => $form -> createView()));
       
    }

    public function deleteCategoryAction($id,Request $request){
        $em = $this -> getDoctrine() -> getManager();        
        $category = $em -> getRepository('LCVPlaylistBundle:PlaylistCategory') -> findOneById($id);
        if (null === $category) {
            throw new NotFoundHttpException("La Categorie d'id " . $id . " n'existe pas.");
        }
        $em -> remove($category);
            $em -> flush();
                    $request -> getSession() -> getFlashBag() -> add('success', 'Catégorie bien supprimée.');
            
        return $this -> redirect($this->getRequest()->headers->get('referer'));
    }
    
    public function playlistPlayerAction($playlist_id,Request $request){
        $em = $this -> getDoctrine() -> getManager();
        $list_audio = $em -> getRepository('LCVPlaylistBundle:PlaylistAudio') -> getAudioFileList($playlist_id);
        
        return $this -> render('LCVPlaylistBundle:Playlist:player_playlist.html.twig', array('list_audio' => $list_audio));
    }

}
