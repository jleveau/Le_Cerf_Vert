<?php

namespace LCV\PlaylistBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use LCV\PlaylistBundle\Entity\Playlist;
use LCV\PlaylistBundle\Entity\PlaylistCategory;
use LCV\PlaylistBundle\Form\PlaylistType;
use LCV\PlaylistBundle\Form\EditPlaylistType;
class PlaylistController extends Controller
{

    
    public function viewAction()
    {
        return $this->render('LCVPlaylistBundle:Playlist:view.html.twig', array(
                // ...
            ));    }

    public function MenuAction($limit=5)
    {
         $listPlaylist = $this -> getDoctrine() -> getManager() -> getRepository('LCVPlaylistBundle:Playlist') -> getLastPlaylists(5);

        return $this -> render('LCVPlaylistBundle:Playlist:menu.html.twig', array('listPlaylist' => $listPlaylist));
    }
    
    public function addAction($categoryId,Request $request)
    {
         $playlist = new Playlist();
        
        $em = $this -> getDoctrine() -> getManager();
        if ($categoryId == null){
            $category=$em->getRepository('LCVPlaylistBundle:PlaylistCategory') -> findOneByName('Default'); 
        }
        else{
            $category=$em->getRepository('LCVPlaylistBundle:PlaylistCategory') -> findOneById($categoryId);
        }
        $playlist->setCategory($category);
    
        $form = $this -> createForm(new PlaylistType(), $playlist);
        if ($form -> handleRequest($request) -> isValid()) {
            $playlist->setAuthor($this->getUser());
          //  $playlist->setRate(new Rating());

            $em -> persist($playlist);
            $em -> flush();

            $request -> getSession() -> getFlashBag() -> add('success', 'Playlist bien enregistrée !');
            return $this -> redirect($this -> generateUrl('lcv_playlist_edit', array('playlist_id' => $playlist -> getId())));
        }
        return $this -> render('LCVPlaylistBundle:Playlist:add.html.twig', array('playlist' => $playlist,'form' => $form -> createView()));
    }


    public function deleteAction()
    {
        return $this->render('LCVPlaylistBundle:Playlist:delete.html.twig', array(
                // ...
            ));    }

    public function editAction($playlist_id,Request $request)
    {
        $em = $this -> getDoctrine() -> getManager();
        $playlist=$em->getRepository('LCVPlaylistBundle:Playlist') -> getOneByIdInOrder($playlist_id);
        
        $form = $this -> createForm(new EditPlaylistType(), $playlist);
        foreach ($playlist->getPlaylistAudios() as $playlistaudio) {
               $playlistaudio->getAudio()->getFile();
           }
        if ($form -> handleRequest($request) -> isValid()) {
            
           foreach ($playlist->getPlaylistAudios() as $playlistaudio) {
                if ($playlistaudio->getAudio()->getTitle()===null){
                    $playlistaudio->getAudio()->setTitle($playlistaudio->getTitle());
                }
               $playlistaudio->getAudio()->upload();               
           }
            $playlist -> updateDate();
            $em -> flush();

            $request -> getSession() -> getFlashBag() -> add('success', 'Playlist mise à jour !');
            return $this -> redirect($this -> generateUrl('lcv_playlist_edit', array('playlist_id' => $playlist -> getId())));
        }
        return $this->render('LCVPlaylistBundle:Playlist:edit.html.twig', array('form' => $form->createView(),'playlist'=>$playlist));    
    }

    public function indexAction()
    {
        return $this->render('LCVPlaylistBundle:Playlist:index.html.twig', array(
                // ...
            ));    }

}
